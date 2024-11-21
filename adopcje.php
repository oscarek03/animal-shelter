<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adopcje</title>
    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <div class="container">
        <button class="custom-btn" onclick="showAddAdoptionAlert()">Dodaj Adopcję</button>
        <div id="addAdoptionAlert" class="custom-alert">
            <button class="close-btn" onclick="hideAddAdoptionAlert()">X</button>
            <h4>Dodaj Adopcję</h4>
            <!-- Select dla zwierząt -->
            <select id="newZwierze" class="form-control mb-2">
                <option value="" disabled selected>Wybierz zwierzę</option>
                <?php
                    // Ustawienie zmiennej środowiskowej dla kodowania
                    putenv('NLS_LANG=AMERICAN_AMERICA.UTF8');

                    // Parametry połączenia do bazy danych Oracle
                    $host = "127.0.0.1";
                    $port = "1521";
                    $service_name = "XEPDB1";
                    $dsn = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port))(CONNECT_DATA=(SERVICE_NAME=$service_name)))";
                    $username = "schronisko";
                    $password = "123";

                    // Połączenie z bazą danych
                    $conn = oci_connect($username, $password, $dsn);
                    if (!$conn) {
                        $e = oci_error();
                        die("Połączenie nieudane: " . $e['message']);
                    }

                    // Zapytanie SQL pobierające dane o zwierzętach
                    $sql = "SELECT z.ID, z.IMIE, z.RASA, k.NUMER 
                    FROM ZWIERZETA z
                    LEFT JOIN KOJCE k ON z.KOJEC_ID = k.KOJEC_ID
                    WHERE z.STATUS = 'Dostepny'";
                    
                    $stid = oci_parse($conn, $sql);

                    // Wykonanie zapytania
                    if (!oci_execute($stid)) {
                        $e = oci_error($stid);
                        die("Błąd wykonania zapytania: " . $e['message']);
                    }
                    // Wyświetlenie wyników w formacie <option>
                    while (($row = oci_fetch_assoc($stid)) != false) {
                        echo "<option value=\"" . $row['ID'] . "\">" . $row['IMIE'] . " (" . $row['RASA'] . ") - " . $row['NUMER'] . "</option>";
                    }

                    // Zwolnienie zasobów i zamknięcie połączenia
                    oci_free_statement($stid);
                    oci_close($conn);
                ?>

            </select>
            <!-- Select dla pracowników -->
            <select id="newPracownik" class="form-control mb-2">
                <option value="" disabled selected>Wybierz pracownika</option>
                <?php
                $conn = oci_connect($username, $password, $dsn);
                $sql = "SELECT ID, IMIE, NAZWISKO FROM Pracownicy WHERE STANOWISKO = 'Koordynator Adopcji'";
                $stid = oci_parse($conn, $sql);
                oci_execute($stid);
                while (($row = oci_fetch_assoc($stid)) != false) {
                    echo "<option value=\"" . $row['ID'] . "\">" . $row['IMIE'] . " " . $row['NAZWISKO'] . "</option>";
                }
                oci_free_statement($stid);
                oci_close($conn);
                ?>
            </select>
            <!-- Select dla adresów -->
            <div class="input-group mb-2">
                <select class="form-control" id="newAdres">
                    <option value="" disabled selected>Wybierz adres</option>
                    <?php
                    $conn = oci_connect($username, $password, $dsn);
                    $sql = "SELECT ID_ADRESU, MIASTO, KOD_POCZTOWY, ULICA, NUMER_DOMU, NUMER_MIESZKANIA FROM Adresy";
                    $stid = oci_parse($conn, $sql);
                    oci_execute($stid);
                    while (($row = oci_fetch_assoc($stid)) != false) {
                        $adres = $row['MIASTO'] . ', ' . $row['KOD_POCZTOWY'] . ', ' . $row['ULICA'] . ' ' . $row['NUMER_DOMU'] . (isset($row['NUMER_MIESZKANIA']) ? '/' . $row['NUMER_MIESZKANIA'] : '');
                        echo "<option value=\"" . $row['ID_ADRESU'] . "\">$adres</option>";
                    }
                    oci_free_statement($stid);
                    oci_close($conn);
                    ?>
                </select>
                <button class="btn btn-plus" onclick="showAddAddressAlert()">+</button>

            </div>

            <input type="text" id="newImie" class="form-control mb-2" placeholder="Imię" required>
            <input type="text" id="newNazwisko" class="form-control mb-2" placeholder="Nazwisko" required>
            <input type="tel" id="newTelefon" class="form-control mb-2" placeholder="Numer telefonu" required>
            
            <!-- Data adopcji -->
            <input type="date" id="newDataAdopcji" class="form-control mb-2" placeholder="Data Adopcji">
            <button class="btn btn-primary" onclick="submitAdoptionForm()">Dodaj</button>
        </div>

        <h2>Lista Adopcji</h2>
        <table id="adoptionTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Zwierzę</th>
                    <th>Koordynator adopcji</th>
                    <th>Adres adoptującego</th>
                    <th>Data adopcji</th>
                    <th>Dane adoptującego</th>
                    <th>Akcja</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $conn = oci_connect($username, $password, $dsn);
                $sql = "SELECT 
                    ra.ID_ADOPCJI, 
                    z.IMIE || ' (' || z.RASA || ')' || ' - ' || k.NUMER  AS ZWIERZE, 
                    p.IMIE || ' ' || p.NAZWISKO AS PRACOWNIK, 
                    ad.MIASTO || ', ' || ad.KOD_POCZTOWY || ', ' || ad.ULICA || ' ' || ad.NUMER_DOMU || COALESCE('/' || ad.NUMER_MIESZKANIA, '') AS ADRES, 
                    ra.DATA_ADOPCJI, ra.imie || ' ' || ra.nazwisko || ' ' || ra.numer_telefonu AS DANE_ADOPTUJACEGO
                FROM REJESTR_ADOPCJI ra
                LEFT JOIN PRACOWNICY p ON ra.PRACOWNIK_ID = p.ID
                LEFT JOIN ZWIERZETA z ON ra.ZWIERZE_ID = z.ID
                LEFT JOIN KOJCE k ON z.KOJEC_ID = k.KOJEC_ID
                LEFT JOIN ADRESY ad ON ra.ADRES_ID = ad.ID_ADRESU";
                $stid = oci_parse($conn, $sql);
                oci_execute($stid);
                while (($row = oci_fetch_assoc($stid)) != false) {
                    echo "<tr>";
                    echo "<td>" . $row['ZWIERZE'] . "</td>";
                    echo "<td>" . $row['PRACOWNIK'] . "</td>";
                    echo "<td>" . $row['ADRES'] . "</td>";
                    echo "<td>" . $row['DATA_ADOPCJI'] . "</td>";
                    echo "<td>" . $row['DANE_ADOPTUJACEGO'] . "</td>";
                    echo "<td><button class='btn btn-danger' onclick='deleteAdoption(" . $row['ID_ADOPCJI'] . ")'>Usuń</button></td>";
                    echo "</tr>";
                }
                oci_free_statement($stid);
                oci_close($conn);
                ?>
            </tbody>
        </table>
    </div>

    <div id="addAddressAlert" class="custom-alert">
    <form onsubmit="submitAddressForm(); return false;">
        <button class="close-btn" type="button" onclick="hideAddAddressAlert()">X</button>
        <h4>Dodaj Adres</h4>
        <input type="text" id="newMiasto" class="form-control mb-2" placeholder="Miasto" required>
        <input type="text" id="newKodPocztowy" class="form-control mb-2" placeholder="Kod Pocztowy" required>
        <input type="text" id="newUlica" class="form-control mb-2" placeholder="Ulica">
        <input type="text" id="newNumerDomu" class="form-control mb-2" placeholder="Numer Domu">
        <input type="text" id="newNumerMieszkania" class="form-control mb-2" placeholder="Numer Mieszkania">
        <button class="btn btn-primary" type="submit">Dodaj</button>
    </form>
</div>

    <script>
        function showAddAdoptionAlert() {
            $('#addAdoptionAlert').show().draggable();
        }

        function hideAddAdoptionAlert() {
            $('#addAdoptionAlert').hide();
        }

        function submitAdoptionForm() {
            var zwierzeId = $('#newZwierze').val();
            var pracownikId = $('#newPracownik').val();
            var adresId = $('#newAdres').val();
            var dataAdopcji = $('#newDataAdopcji').val();
            var imie = $('#newImie').val();
            var nazwisko = $('#newNazwisko').val();
            var telefon = $('#newTelefon').val();

            console.log({
                id_zwierzecia: zwierzeId,
                id_pracownika: pracownikId,
                id_adresu: adresId,
                data_adopcji: dataAdopcji,
                imie: imie,
                nazwisko: nazwisko,
                telefon: telefon
            });


            $.ajax({
                url: 'add_adoption.php',
                type: 'POST',
                data: {
                    id_zwierzecia: zwierzeId,
                    id_pracownika: pracownikId,
                    id_adresu: adresId,
                    data_adopcji: dataAdopcji,
                    imie: imie,
                    nazwisko: nazwisko,
                    telefon: telefon
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Adopcja została dodana!');
                        hideAddAdoptionAlert();
                        location.reload();
                    } else {
                        alert('Wystąpił błąd: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Wystąpił błąd: ' + error);
                }
            });
        }
        function submitAddressForm() {
            var miasto = $('#newMiasto').val();
            var kodPocztowy = $('#newKodPocztowy').val();
            var ulica = $('#newUlica').val();
            var numerDomu = $('#newNumerDomu').val();
            var numerMieszkania = $('#newNumerMieszkania').val();

            $.ajax({
                url: 'add_address.php',
                type: 'POST',
                data: {
                    miasto: miasto,
                    kod_pocztowy: kodPocztowy,
                    ulica: ulica,
                    numer_domu: numerDomu,
                    numer_mieszkania: numerMieszkania
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Adres został dodany!');
                        hideAddAddressAlert();
                        location.reload();
                    } else {
                        alert('Wystąpił błąd: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Wystąpił błąd: ' + error);
                }
            });
        }

        $(document).ready(function() {
            $('#adoptionTable').DataTable();
        });

        function showAddAddressAlert() {
            $('#addAddressAlert').show().draggable();
        }

        function hideAddAddressAlert() {
            $('#addAddressAlert').hide();
        }

                // Funkcja do usuwania adopcji z użyciem AJAX
        function deleteAdoption(adopcjeId) {
            $.ajax({
                url: 'delete_adoption.php', // Skrypt PHP obsługujący usuwanie adopcji
                type: 'POST',
                data: {
                    id: adopcjeId // Przekazujemy ID adopcji do skryptu
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Adopcja została usunięta!');
                        location.reload(); // Odświeżamy stronę po udanym usunięciu
                    } else {
                        alert('Wystąpił błąd: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Wystąpił błąd: ' + error);
                }
            });
        }


    </script>
</body>
</html>
