<?php
    require_once "../db_connection.php";

    // Pobieranie danych z żądania POST
    $id = $_POST["id"];
    $nazwa_uzytkownika = $_POST["nazwa_uzytkownika"];
    $imie = $_POST["imie"];
    $nazwisko = $_POST["nazwisko"];
    $mail = $_POST["email"]; // Poprawione

    try {
        // Przygotowanie wywołania procedury
        $sql =
            "BEGIN edytuj_darczynce(:id, :nazwa_uzytkownika, :imie, :nazwisko, :mail); END;";
        $stid = oci_parse($conn, $sql);

        // Mapowanie zmiennych PHP na parametry SQL
        oci_bind_by_name($stid, ":id", $id);
        oci_bind_by_name($stid, ":nazwa_uzytkownika", $nazwa_uzytkownika);
        oci_bind_by_name($stid, ":imie", $imie);
        oci_bind_by_name($stid, ":nazwisko", $nazwisko);
        oci_bind_by_name($stid, ":mail", $mail);

        // Wykonanie zapytania
        $result = oci_execute($stid);

        if ($result) {
            echo json_encode([
                "success" => true,
                "message" => "Darczyńca został pomyślnie zaktualizowany.",
            ]);
        } else {
            $e = oci_error($stid);
            echo json_encode([
                "success" => false,
                "message" => "Błąd podczas edycji darczyńcy: " . $e["message"],
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
