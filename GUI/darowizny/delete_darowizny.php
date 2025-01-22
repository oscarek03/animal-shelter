<?php
    require_once "../db_connection.php";

    // Pobieramy ID darowizny do usunięcia
    $darowizna_id = $_POST["id"];

    try {
        // Przygotowujemy wywołanie procedury
        $sql = "BEGIN usun_darowizne(:id); END;";
        $stid = oci_parse($conn, $sql);

        // Bindowanie parametru ID
        oci_bind_by_name($stid, ":id", $darowizna_id);

        // Wykonujemy zapytanie
        $result = oci_execute($stid);

        if ($result) {
            echo json_encode([
                "success" => true,
                "message" => "Darowizna została pomyślnie usunięta.",
            ]);
        } else {
            $e = oci_error($stid);
            echo json_encode([
                "success" => false,
                "message" => "Błąd podczas usuwania darowizny: " . $e["message"],
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "Wyjątek: " . $e->getMessage(),
        ]);
    } finally {
        // Zwalniamy zasoby
        if ($stid) {
            oci_free_statement($stid);
        }
        if ($conn) {
            oci_close($conn);
        }
    }
?>
