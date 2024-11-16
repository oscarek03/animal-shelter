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

// Połączenie z bazą danych
$conn = oci_connect($username, $password, $dsn);
if (!$conn) {
    $e = oci_error();
    echo json_encode(['success' => false, 'message' => $e['message']]);
    exit;
}

// Pobranie ID zwierzęcia do usunięcia
$animal_id = $_POST['id'];

if (empty($animal_id)) {
    echo json_encode(['success' => false, 'message' => 'Brak ID zwierzęcia.']);
    exit;
}

// Przygotowanie wywołania procedury lub zapytania
$sql = "BEGIN usun_zwierze(:id); END;";
$stid = oci_parse($conn, $sql);

// Bindowanie parametru ID
oci_bind_by_name($stid, ":id", $animal_id);

// Wykonanie zapytania
$result = oci_execute($stid);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Zwierzę zostało pomyślnie usunięte.']);
} else {
    $e = oci_error($stid);
    echo json_encode(['success' => false, 'message' => $e['message']]);
}

// Zwolnienie zasobów i zamknięcie połączenia
oci_free_statement($stid);
oci_close($conn);
?>
