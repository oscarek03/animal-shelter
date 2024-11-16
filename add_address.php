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
$miasto = $_POST['miasto'];
$kod_pocztowy = $_POST['kod_pocztowy'];
$ulica = $_POST['ulica'];
$numer_domu = $_POST['numer_domu'];
$numer_mieszkania = $_POST['numer_mieszkania'];

// Przygotowujemy wywołanie procedury
$sql = "BEGIN DODAJ_ADRES(:miasto, :kod_pocztowy, :ulica, :numer_domu, :numer_mieszkania); END;";
$stid = oci_parse($conn, $sql);

// Bindowanie parametrów
oci_bind_by_name($stid, ":miasto", $miasto);
oci_bind_by_name($stid, ":kod_pocztowy", $kod_pocztowy);
oci_bind_by_name($stid, ":ulica", $ulica);
oci_bind_by_name($stid, ":numer_domu", $numer_domu);
oci_bind_by_name($stid, ":numer_mieszkania", $numer_mieszkania);

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
