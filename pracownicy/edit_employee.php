<?php
    require_once "../db_connection.php";

    $id = $_POST["id"];
    $imie = $_POST["imie"];
    $nazwisko = $_POST["nazwisko"];
    $pensja = $_POST["pensja"];
    $stanowisko = $_POST["stanowisko"];
    $adres_id = $_POST["adres_id"];
    $data_zatrudnienia = $_POST["data_zatrudnienia"];

    $sql =
        "BEGIN edytuj_pracownika(:id, :imie, :nazwisko, :pensja, :stanowisko, :adres_id, TO_DATE(:data_zatrudnienia, 'YYYY-MM-DD')); END;";
    $stid = oci_parse($conn, $sql);

    oci_bind_by_name($stid, ":id", $id);
    oci_bind_by_name($stid, ":imie", $imie);
    oci_bind_by_name($stid, ":nazwisko", $nazwisko);
    oci_bind_by_name($stid, ":pensja", $pensja);
    oci_bind_by_name($stid, ":stanowisko", $stanowisko);
    oci_bind_by_name($stid, ":adres_id", $adres_id);
    oci_bind_by_name($stid, ":data_zatrudnienia", $data_zatrudnienia);

    $result = oci_execute($stid);

    if ($result) {
        echo json_encode(["success" => true]);
    } else {
        $e = oci_error($stid);
        echo json_encode(["success" => false, "message" => $e["message"]]);
    }

    oci_free_statement($stid);
    oci_close($conn);
?>
