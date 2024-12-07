<?php
    // db_connection.php
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
?>
