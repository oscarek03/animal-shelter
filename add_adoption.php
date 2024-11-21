<?php
    putenv('NLS_LANG=AMERICAN_AMERICA.UTF8');
    header('Content-Type: application/json'); // Ustawienie JSON jako typ treści
    error_reporting(0); // Ukrywanie ostrzeżeń i błędów

    $host = "127.0.0.1";
    $port = "1521";
    $service_name = "XEPDB1";
    $dsn = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port))(CONNECT_DATA=(SERVICE_NAME=$service_name)))";
    $username = "schronisko";
    $password = "123";

    $conn = oci_connect($username, $password, $dsn);
    if (!$conn) {
        $e = oci_error();
        echo json_encode(['success' => false, 'message' => $e['message']]);
        exit;
    }

    // Pobieramy dane z formularza
    $id_zwierzecia = $_POST['id_zwierzecia'];
    $id_pracownika = $_POST['id_pracownika'];
    $id_adresu = $_POST['id_adresu'];
    $data_adopcji = $_POST['data_adopcji'];
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $telefon = $_POST['telefon'];

    // Przygotowanie wywołania procedury do dodania adopcji
    $sql = "BEGIN dodaj_adopcje(:id_zwierzecia, :id_pracownika, :id_adresu, TO_DATE(:data_adopcji, 'YYYY-MM-DD'), :imie, :nazwisko, :telefon); END;";
    $stid = oci_parse($conn, $sql);

    // Bindowanie parametrów
    oci_bind_by_name($stid, ":id_zwierzecia", $id_zwierzecia);
    oci_bind_by_name($stid, ":id_pracownika", $id_pracownika);
    oci_bind_by_name($stid, ":id_adresu", $id_adresu);
    oci_bind_by_name($stid, ":data_adopcji", $data_adopcji);
    oci_bind_by_name($stid, ":imie", $imie);
    oci_bind_by_name($stid, ":nazwisko", $nazwisko);
    oci_bind_by_name($stid, ":telefon", $telefon);

    // Wykonujemy zapytanie
    $result = oci_execute($stid);

    // Jeśli adopcja się udała, zmieniamy status zwierzęcia
    if ($result) {
        // Zmiana statusu zwierzęcia na "Adoptowany"
        $update_sql = "UPDATE ZWIERZETA SET STATUS = 'Adoptowany' WHERE ID = :id_zwierzecia";
        $update_stid = oci_parse($conn, $update_sql);
        oci_bind_by_name($update_stid, ":id_zwierzecia", $id_zwierzecia);

        // Wykonanie zapytania zmieniającego status
        $update_result = oci_execute($update_stid);

        if ($update_result) {
            echo json_encode(['success' => true]);
        } else {
            $e = oci_error($update_stid);
            echo json_encode(['success' => false, 'message' => $e['message']]);
        }

        oci_free_statement($update_stid);
    } else {
        $e = oci_error($stid);
        echo json_encode(['success' => false, 'message' => $e['message']]);
    }

    oci_free_statement($stid);
    oci_close($conn);

    // Logowanie danych wejściowych dla debugowania
    error_log("Dane: " . print_r($_POST, true));
?>
