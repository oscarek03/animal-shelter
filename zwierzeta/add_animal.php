<?php
    require_once '../db_connection.php';
    
    // Pobieramy dane z formularza
    $imie = $_POST['imie'];
    $rasa = $_POST['rasa'];
    $plec = $_POST['plec'];
    $status = $_POST['status'];
    $kojec_id = $_POST['numer_kojca'];
    $data_przyjecia = $_POST['data_przyjecia'];
    $wiek = $_POST['wiek'];
    $typ = $_POST['typ'];

    // Przygotowujemy wywołanie procedury
    $sql = "BEGIN dodaj_zwierze(:imie, :rasa, :plec, :status, :kojec_id, TO_DATE(:data_przyjecia, 'YYYY-MM-DD'), :wiek, :typ); END;";
    $stid = oci_parse($conn, $sql);

    // Bindowanie parametrów
    oci_bind_by_name($stid, ":imie", $imie);
    oci_bind_by_name($stid, ":rasa", $rasa);
    oci_bind_by_name($stid, ":plec", $plec);
    oci_bind_by_name($stid, ":status", $status);
    oci_bind_by_name($stid, ":kojec_id", $kojec_id);
    oci_bind_by_name($stid, ":data_przyjecia", $data_przyjecia);
    oci_bind_by_name($stid, ":wiek", $wiek);
    oci_bind_by_name($stid, ":typ", $typ);

    // Wykonujemy zapytanie
    $result = oci_execute($stid);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        $e = oci_error($stid);
        echo json_encode(['success' => false, 'message' => $e['message']]);
    }

    oci_free_statement($stid);
    oci_close($conn);

    // Logowanie danych wejściowych dla debugowania
    error_log("Dane: " . print_r($_POST, true));
?>
