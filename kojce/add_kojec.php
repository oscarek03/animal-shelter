<?php
    require_once "../db_connection.php";

    // Pobieramy dane z formularza
    $numer = $_POST["numer"];
    $rozmiar = $_POST["rozmiar"];

    // Przygotowujemy wywołanie procedury
    $sql = "BEGIN DODAJ_KOJEC(:numer, :rozmiar); END;";
    $stid = oci_parse($conn, $sql);

    // Bindowanie parametrów
    oci_bind_by_name($stid, ":numer", $numer);
    oci_bind_by_name($stid, ":rozmiar", $rozmiar);

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

    // Logowanie danych wejściowych dla debugowania
    error_log("Dane: " . print_r($_POST, true));
?>
