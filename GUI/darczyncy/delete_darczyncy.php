<?php
    require_once "../db_connection.php";

    // Pobieramy ID darczyńcy do usunięcia
    $darczynca_id = $_POST["id"];

    // Przygotowujemy wywołanie procedury
    $sql = "BEGIN usun_darczynce(:id); END;";
    $stid = oci_parse($conn, $sql);

    // Bindowanie parametru ID
    oci_bind_by_name($stid, ":id", $darczynca_id);

    // Wykonujemy zapytanie
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
