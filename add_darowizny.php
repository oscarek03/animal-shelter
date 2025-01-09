<?php
require_once 'db_connection.php';

// Pobieramy dane z formularza
$darczynca_id = $_POST['darczynca_id'];
$kwota = $_POST['kwota'];
$data = $_POST['data'];

try {
    // Przygotowanie wywołania procedury
    $sql = "BEGIN DODAJ_DAROWIZNE(:darczynca_id, :kwota, TO_DATE(:data, 'YYYY-MM-DD')); END;";
    $stid = oci_parse($conn, $sql);

    // Bindowanie parametrów
    oci_bind_by_name($stid, ":darczynca_id", $darczynca_id);
    oci_bind_by_name($stid, ":kwota", $kwota);
    oci_bind_by_name($stid, ":data", $data);

    // Wykonanie zapytania
    $result = oci_execute($stid);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Darowizna została dodana pomyślnie.']);
    } else {
        $e = oci_error($stid);
        echo json_encode(['success' => false, 'message' => 'Błąd podczas dodawania darowizny: ' . $e['message']]);
    }

    // Zakończenie połączenia
    oci_free_statement($stid);
    oci_close($conn);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Wyjątek: ' . $e->getMessage()]);
}

// Logowanie danych wejściowych dla debugowania
error_log("Dane: " . print_r($_POST, true));
?>
