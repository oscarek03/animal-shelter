<?php
putenv('NLS_LANG=AMERICAN_AMERICA.UTF8');
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

// Pobieranie danych z żądania POST
$id_adresu = $_POST['id_adresu'];
$miasto = $_POST['miasto'];
$kod_pocztowy = $_POST['kod_pocztowy'];
$ulica = $_POST['ulica'];
$numer_domu = $_POST['numer_domu'];
$numer_mieszkania = $_POST['numer_mieszkania'];

// Wywołanie procedury edytuj_adres
$sql = "BEGIN edytuj_adres(:id_adresu, :miasto, :kod_pocztowy, :ulica, :numer_domu, :numer_mieszkania); END;";
$stid = oci_parse($conn, $sql);

// Mapowanie zmiennych PHP na parametry SQL
oci_bind_by_name($stid, ":id_adresu", $id_adresu);
oci_bind_by_name($stid, ":miasto", $miasto);
oci_bind_by_name($stid, ":kod_pocztowy", $kod_pocztowy);
oci_bind_by_name($stid, ":ulica", $ulica);
oci_bind_by_name($stid, ":numer_domu", $numer_domu);
oci_bind_by_name($stid, ":numer_mieszkania", $numer_mieszkania);

// Wykonanie zapytania
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
