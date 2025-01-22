<?php
    require_once "../db_connection.php";

    // Pobieranie danych z żądania POST
    $id = $_POST["id"];
    $darczynca_id = $_POST["darczynca_id"];
    $kwota = $_POST["kwota"];
    $data = $_POST["data"];

    try {
        // Przygotowanie wywołania procedury
        $sql =
            "BEGIN edytuj_darowizne(:id, :darczynca_id, :kwota, TO_DATE(:data, 'YYYY-MM-DD')); END;";
        $stid = oci_parse($conn, $sql);

        // Mapowanie zmiennych PHP na parametry SQL
        oci_bind_by_name($stid, ":id", $id);
        oci_bind_by_name($stid, ":darczynca_id", $darczynca_id);
        oci_bind_by_name($stid, ":kwota", $kwota);
        oci_bind_by_name($stid, ":data", $data);

        // Wykonanie zapytania
        $result = oci_execute($stid);

        if ($result) {
            echo json_encode([
                "success" => true,
                "message" => "Darowizna została pomyślnie zaktualizowana.",
            ]);
        } else {
            $e = oci_error($stid);
            echo json_encode([
                "success" => false,
                "message" => "Błąd podczas edycji darowizny: " . $e["message"],
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "Wyjątek: " . $e->getMessage(),
        ]);
    } finally {
        // Zwolnienie zasobów i zamknięcie połączenia
        if ($stid) {
            oci_free_statement($stid);
        }
        if ($conn) {
            oci_close($conn);
        }
    }
?>
