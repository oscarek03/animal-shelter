<?php
header('Content-Type: application/json'); // Ustawienie JSON jako typ treści
error_reporting(0); // Ukrywanie ostrzeżeń i błędów

$host = "127.0.0.1";
$port = "1521";
$service_name = "XEPDB1";
$dsn = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port))(CONNECT_DATA=(SERVICE_NAME=$service_name)))";
$username = "schronisko";
$password = "123";

$conn = oci_connect($username, $password, $dsn);
if (!$conn) {
    $e = oci_error();
    echo json_encode(['success' => false, 'message' => $e['message']]);
    exit;
}

// Pobieramy dane z formularza
$imie = $_POST['imie'];
$nazwisko = $_POST['nazwisko'];
$pensja = $_POST['pensja'];
$stanowisko = $_POST['stanowisko'];
$adres_id = $_POST['adres_id'];
$data_zatrudnienia = $_POST['data_zatrudnienia'];

// Przygotowujemy wywołanie procedury
$sql = "BEGIN dodaj_pracownika(:imie, :nazwisko, :pensja, :stanowisko, :adres_id, TO_DATE(:data_zatrudnienia, 'YYYY-MM-DD')); END;";
$stid = oci_parse($conn, $sql);

// Bindowanie parametrów
oci_bind_by_name($stid, ":imie", $imie);
oci_bind_by_name($stid, ":nazwisko", $nazwisko);
oci_bind_by_name($stid, ":pensja", $pensja);
oci_bind_by_name($stid, ":stanowisko", $stanowisko);
oci_bind_by_name($stid, ":adres_id", $adres_id);
oci_bind_by_name($stid, ":data_zatrudnienia", $data_zatrudnienia);

// Wykonujemy zapytanie
$result = oci_execute($stid);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    $e = oci_error($stid);
    echo json_encode(['success' => false, 'message' => $e['message']]);
}

oci_free_statement($stid);
oci_close($conn);

// Logowanie danych wejściowych dla debugowania
error_log("Dane: " . print_r($_POST, true));
?>
