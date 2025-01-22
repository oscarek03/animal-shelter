<?php
    require_once "../db_connection.php";

    // Pobranie ID zwierzęcia do usunięcia
    $animal_id = $_POST["id"];

    if (empty($animal_id)) {
        echo json_encode(["success" => false, "message" => "Brak ID zwierzęcia."]);
        exit();
    }

    // Przygotowanie wywołania procedury lub zapytania
    $sql = "BEGIN usun_zwierze(:id); END;";
    $stid = oci_parse($conn, $sql);

    // Bindowanie parametru ID
    oci_bind_by_name($stid, ":id", $animal_id);

    // Wykonanie zapytania
    $result = oci_execute($stid);

    if ($result) {
        echo json_encode([
            "success" => true,
            "message" => "Zwierzę zostało pomyślnie usunięte.",
        ]);
    } else {
        $e = oci_error($stid);
        echo json_encode(["success" => false, "message" => $e["message"]]);
    }

    // Zwolnienie zasobów i zamknięcie połączenia
    oci_free_statement($stid);
    oci_close($conn);
?>
