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

// Pobieramy ID adresu do usunięcia
$adres_id = $_POST['id'];

// Przygotowujemy wywołanie procedury
$sql = "BEGIN usun_adres(:id); END;";
$stid = oci_parse($conn, $sql);

// Bindowanie parametru ID
oci_bind_by_name($stid, ":id", $adres_id);

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
?>
