<?php
putenv('NLS_LANG=AMERICAN_AMERICA.UTF8');
header('Content-Type: application/json'); // Ustawienie JSON jako typ treści
error_reporting(0); // Ukrywanie ostrzeżeń i błędów

$host = "127.0.0.1";
$port = "1521";
$service_name = "XEPDB1";
$dsn = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port))(CONNECT_DATA=(SERVICE_NAME=$service_name)))";
$username = "schronisko";
$password = "123";

// Łączenie z bazą danych
$conn = oci_connect($username, $password, $dsn);
if (!$conn) {
    $e = oci_error();
    echo json_encode(['success' => false, 'message' => $e['message']]);
    exit;
}

// Pobieramy ID adopcji do usunięcia
$adopcja_id = $_POST['id'];

// Przygotowujemy zapytanie, aby pobrać ID zwierzęcia powiązanego z adopcją
$get_zwierze_sql = "SELECT ZWIERZE_ID FROM REJESTR_ADOPCJI WHERE ID_ADOPCJI = :id";
$get_zwierze_stid = oci_parse($conn, $get_zwierze_sql);
oci_bind_by_name($get_zwierze_stid, ":id", $adopcja_id);

// Wykonanie zapytania
oci_execute($get_zwierze_stid);
$zwierze_row = oci_fetch_assoc($get_zwierze_stid);

// Jeśli znaleziono zwierzę, zmieniamy jego status na "Dostępny"
if ($zwierze_row) {
    $id_zwierzecia = $zwierze_row['ZWIERZE_ID'];

    // Zmiana statusu zwierzęcia na "Dostępny"
    $update_status_sql = "UPDATE ZWIERZETA SET STATUS = 'Dostepny' WHERE ID = :id_zwierzecia";
    $update_status_stid = oci_parse($conn, $update_status_sql);
    oci_bind_by_name($update_status_stid, ":id_zwierzecia", $id_zwierzecia);
    
    // Wykonanie zapytania zmieniającego status zwierzęcia
    $update_result = oci_execute($update_status_stid);

    if (!$update_result) {
        $e = oci_error($update_status_stid);
        echo json_encode(['success' => false, 'message' => 'Błąd zmiany statusu zwierzęcia: ' . $e['message']]);
        oci_free_statement($update_status_stid);
        oci_free_statement($get_zwierze_stid);
        oci_close($conn);
        exit;
    }

    // Zwolnienie zasobów zapytania o zmianę statusu
    oci_free_statement($update_status_stid);
} else {
    echo json_encode(['success' => false, 'message' => 'Nie znaleziono zwierzęcia związane z tą adopcją.']);
    oci_free_statement($get_zwierze_stid);
    oci_close($conn);
    exit;
}

// Przygotowujemy wywołanie procedury usuwającej adopcję
$sql = "BEGIN usun_adopcje(:id); END;";
$stid = oci_parse($conn, $sql);

// Bindowanie parametru ID adopcji
oci_bind_by_name($stid, ":id", $adopcja_id);

// Wykonujemy zapytanie usuwające adopcję
$result = oci_execute($stid);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    $e = oci_error($stid);
    echo json_encode(['success' => false, 'message' => $e['message']]);
}

// Zwolnienie zasobów i zamknięcie połączenia
oci_free_statement($stid);
oci_close($conn);
?>
