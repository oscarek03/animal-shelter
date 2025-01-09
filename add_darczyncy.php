<?php
require_once 'db_connection.php';

// Pobieramy dane z formularza
$nazwa_uzytkownika = $_POST['nazwa_uzytkownika'];
$imie = $_POST['imie'];
$nazwisko = $_POST['nazwisko'];
$mail = $_POST['email'];  // Poprawione

// Przygotowujemy wywołanie procedury
$sql = "BEGIN DODAJ_DARCZYNCE(:nazwa_uzytkownika, :imie, :nazwisko, :mail); END;";
$stid = oci_parse($conn, $sql);

// Bindowanie parametrów
oci_bind_by_name($stid, ":nazwa_uzytkownika", $nazwa_uzytkownika);
oci_bind_by_name($stid, ":imie", $imie);
oci_bind_by_name($stid, ":nazwisko", $nazwisko);
oci_bind_by_name($stid, ":mail", $mail);  // Poprawione

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
