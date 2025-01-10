<?php
    require_once '../db_connection.php';

    try {
        // Pobranie ID kojca do usunięcia
        $kojec_id = $_POST['id'];

        if (empty($kojec_id)) {
            throw new Exception("Brak ID kojca.");
        }

        // Przygotowanie wywołania procedury
        $sql = "BEGIN USUN_KOJEC(:id); END;";
        $stid = oci_parse($conn, $sql);

        // Bindowanie parametru ID
        oci_bind_by_name($stid, ":id", $kojec_id);

        // Wykonanie zapytania
        $result = oci_execute($stid);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Kojec został pomyślnie usunięty.']);
        } else {
            $e = oci_error($stid);
            throw new Exception("Błąd podczas usuwania kojca: " . $e['message']);
        }
    } catch (Exception $e) {
        // Zwracanie błędu w formacie JSON
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } finally {
        // Zwolnienie zasobów i zamknięcie połączenia
        if (isset($stid)) {
            oci_free_statement($stid);
        }
        if (isset($conn)) {
            oci_close($conn);
        }
    }
?>
