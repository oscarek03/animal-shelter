<?php
    require_once '../db_connection.php';

    // Pobieranie danych z żądania POST
    $id_kojca = $_POST['kojec_id'];
    $numer_kojca = $_POST['numer_kojca'];
    $rozmiar_kojca = $_POST['rozmiar_kojca'];
    

    // Wywołanie procedury EDYTUJ_KOJEC
    $sql = "BEGIN EDYTUJ_KOJEC(:kojec_id, :numer_kojca, :rozmiar_kojca); END;";
    $stid = oci_parse($conn, $sql);

    // Mapowanie zmiennych PHP na parametry SQL
    oci_bind_by_name($stid, ":kojec_id", $id_kojca);
    oci_bind_by_name($stid, ":numer_kojca", $numer_kojca);
    oci_bind_by_name($stid, ":rozmiar_kojca", $rozmiar_kojca);

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
