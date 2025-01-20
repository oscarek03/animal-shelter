<?php
    require_once "../db_connection.php";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $animalId = $_POST["animalId"];
        $koordynator = $_POST["koordynator"];
        $opiekun = $_POST["opiekun"];
        $sprzatacz = $_POST["sprzatacz"];
        $weterynarz = $_POST["weterynarz"];

        try {
            // Usuń istniejące przypisania
            $deleteSql =
                "DELETE FROM pracownik_zwierzeta WHERE zwierze_id = :animalId";
            $deleteStmt = oci_parse($conn, $deleteSql);
            oci_bind_by_name($deleteStmt, ":animalId", $animalId);
            oci_execute($deleteStmt);

            // Dodaj nowe przypisania
            $insertSql =
                "INSERT INTO pracownik_zwierzeta (zwierze_id, pracownik_id) VALUES (:animalId, :workerId)";
            $insertStmt = oci_parse($conn, $insertSql);

            $positions = [$koordynator, $opiekun, $sprzatacz, $weterynarz];

            foreach ($positions as $workerId) {
                if (!empty($workerId)) {
                    oci_bind_by_name($insertStmt, ":animalId", $animalId);
                    oci_bind_by_name($insertStmt, ":workerId", $workerId);
                    oci_execute($insertStmt);
                }
            }

            echo json_encode(["status" => "success"]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
        oci_commit($conn);
    }
?>
