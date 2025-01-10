<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Podłączenie arkusza stylów oraz bibliotek Bootstrap, jQuery i jQuery UI -->
    <link rel="stylesheet" href="../styles/style1.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://markcell.github.io/jquery-tabledit/jquery.tabledit.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container" style="margin-top: 100px;">
        <?php
        // Połączenie z bazą danych i wywołanie funkcji PL/SQL
        require_once '../db_connection.php';

        try {
            // Wywołanie funkcji PL/SQL, która zwraca dane o zwierzętach i przypisanych pracownikach
            $sql = 'BEGIN :cursor := PobierzZwierzetaPracownicy(); END;';
            $stid = oci_parse($conn, $sql);

            // Tworzymy kursor do pobrania danych
            $cursor = oci_new_cursor($conn);

            // Przypisujemy kursor do zmiennej wyjściowej
            oci_bind_by_name($stid, ':cursor', $cursor, -1, OCI_B_CURSOR);

            // Wykonujemy zapytanie PL/SQL oraz uruchamiamy kursor
            oci_execute($stid);
            oci_execute($cursor);

            // Tworzymy tablicę na wyniki z kursora
            $zwierzeta = [];

            // Iterujemy wyniki i przypisujemy dane do tablicy
            while (($row = oci_fetch_assoc($cursor)) != false) {
                $zwierzeId = $row['ZWIERZE_ID'];

                // Sprawdzamy, czy zwierzę o danym ID już istnieje w tablicy
                if (!isset($zwierzeta[$zwierzeId])) {
                    $zwierzeta[$zwierzeId] = [
                        'IMIE' => $row['ZWIERZE_IMIE'],
                        'RASA' => $row['ZWIERZE_RASA'],
                        'TYP' => $row['ZWIERZE_TYP'],
                        'WETERYNARZ' => '',
                        'SPRZATACZ' => '',
                        'KOORDYNATOR_ADOPCJI' => '',
                        'OPIEKUN' => ''
                    ];
                }

                // Przypisujemy odpowiednich pracowników do zwierząt na podstawie stanowiska
                if (!empty($row['PRACOWNIK_STANOWISKO'])) {
                    $stanowisko = $row['PRACOWNIK_STANOWISKO'];
                    $pracownik = $row['PRACOWNIK_IMIE'] . ' ' . $row['PRACOWNIK_NAZWISKO'];

                    switch ($stanowisko) {
                        case 'Weterynarz':
                            $zwierzeta[$zwierzeId]['WETERYNARZ'] = $pracownik;
                            break;
                        case 'Sprzatacz':
                            $zwierzeta[$zwierzeId]['SPRZATACZ'] = $pracownik;
                            break;
                        case 'Koordynator Adopcji':
                            $zwierzeta[$zwierzeId]['KOORDYNATOR_ADOPCJI'] = $pracownik;
                            break;
                        case 'Opiekun Zwierzat':
                            $zwierzeta[$zwierzeId]['OPIEKUN'] = $pracownik;
                            break;
                    }
                }
            }

            // Generowanie tabeli HTML z danymi zwierząt i pracowników
            echo '<table id="workersTable" class="table table-bordered">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Id zwierzecia</th>';
            echo '<th>Imię</th>';
            echo '<th>Rasa</th>';
            echo '<th>Typ</th>';
            echo '<th>Weterynarz</th>';
            echo '<th>Sprzatacz</th>';
            echo '<th>Koordynator adopcji</th>';
            echo '<th>Opiekun zwierzecia</th>';
            echo '<th>Akcja</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            // Iterowanie po tablicy danych i wyświetlanie wierszy tabeli
            foreach ($zwierzeta as $id => $dane) {
                $animalDataJson = htmlspecialchars(json_encode([
                    'id' => $id,
                    'name' => $dane['IMIE'],
                    'rasa' => $dane['RASA'],
                    'typ' => $dane['TYP'],
                    'animalId' => $id,
                ]), ENT_QUOTES, 'UTF-8');

                echo '<tr>';
                echo '<td>' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . htmlspecialchars($dane['IMIE'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . htmlspecialchars($dane['RASA'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . htmlspecialchars($dane['TYP'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . htmlspecialchars($dane['WETERYNARZ'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . htmlspecialchars($dane['SPRZATACZ'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . htmlspecialchars($dane['KOORDYNATOR_ADOPCJI'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . htmlspecialchars($dane['OPIEKUN'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td class="right-align"><button onclick="showEditWorkersAlert(' . $animalDataJson . ')" class="btn btn-light"><i class="fa fa-user"></i></button></td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';

            // Zwalnianie zasobów po zakończeniu pracy z bazą danych
            oci_free_statement($stid);
            oci_free_statement($cursor);

        } catch (Exception $e) {
            // Obsługa błędów i wyświetlanie komunikatu
            echo "Wystąpił błąd: " . htmlspecialchars($e->getMessage());
        }
        ?>

        <!-- Formularz do edycji pracowników przypisanych do zwierząt -->
        <div id="showEditWorkers" class="custom-alert">
            <button class="close-btn" onclick="hideEditWorkersAlert()">X</button>
            <h4>Edytuj pracowników</h4>

            <!-- Pole wyboru weterynarza -->
            <h6>Weterynarz:</h6>
            <select class="form-control mb-2" id="editWeterynarz">
                <option value="" disabled selected>Wybierz weterynarza</option>
                <?php
                // Pobieranie listy weterynarzy z bazy danych
                $sql = "BEGIN :cursor := get_weterynarz(); END;";
                $stid = oci_parse($conn, $sql);
                $cursor = oci_new_cursor($conn);
                oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);
                oci_execute($stid);
                oci_execute($cursor);
                while (($row = oci_fetch_assoc($cursor)) != false) {
                    echo "<option value=\"" . $row['ID'] . "\">" . $row['ID'] . ") " . $row['IMIE'] . " " . $row['NAZWISKO'] . "</option>";
                }
                oci_free_statement($stid);
                oci_free_statement($cursor);
                ?>
            </select>

            <!-- Analogiczne pola wyboru dla pozostałych pracowników -->
            <h6>Sprzątacz:</h6>
            <select class="form-control mb-2" id="editSprzatacz">
                <option value="" disabled selected>Wybierz sprzątacza</option>
                <?php
                $sql = "BEGIN :cursor := get_sprzatacze(); END;";
                $stid = oci_parse($conn, $sql);
                $cursor = oci_new_cursor($conn);
                oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);
                oci_execute($stid);
                oci_execute($cursor);
                while (($row = oci_fetch_assoc($cursor)) != false) {
                    echo "<option value=\"" . $row['ID'] . "\">" . $row['ID'] . ") " . $row['IMIE'] . " " . $row['NAZWISKO'] . "</option>";
                }
                oci_free_statement($stid);
                oci_free_statement($cursor);
                ?>
            </select>

            <!-- Dalsza część formularza analogicznie -->
            <h6>Koordynator adopcji:</h6>
            <select class="form-control mb-2" id="editKoordynator">
                <option value="" disabled selected>Wybierz koordynatora</option>
                <?php
                $sql = "BEGIN :cursor := get_koordynatorzy_adopcji(); END;";
                $stid = oci_parse($conn, $sql);
                $cursor = oci_new_cursor($conn);
                oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);
                oci_execute($stid);
                oci_execute($cursor);
                while (($row = oci_fetch_assoc($cursor)) != false) {
                    echo "<option value=\"" . $row['ID'] . "\">" . $row['ID'] . ") " . $row['IMIE'] . " " . $row['NAZWISKO'] . "</option>";
                }
                oci_free_statement($stid);
                oci_free_statement($cursor);
                ?>
            </select>

            <h6>Opiekun:</h6>
            <select class="form-control mb-2" id="editOpiekun">
                <option value="" disabled selected>Wybierz opiekuna</option>
                <?php
                $sql = "BEGIN :cursor := get_opiekun(); END;";
                $stid = oci_parse($conn, $sql);
                $cursor = oci_new_cursor($conn);
                oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);
                oci_execute($stid);
                oci_execute($cursor);
                while (($row = oci_fetch_assoc($cursor)) != false) {
                    echo "<option value=\"" . $row['ID'] . "\">" . $row['ID'] . ") " . $row['IMIE'] . " " . $row['NAZWISKO'] . "</option>";
                }
                oci_free_statement($stid);
                oci_free_statement($cursor);
                ?>
            </select>
            
            <!-- Przycisk do zapisywania zmian -->
            <button class="btn btn-primary" onclick="submitWorkersForm()">Zapisz</button>
        </div>
    </div>

    <!-- Skrypt JavaScript do obsługi edycji danych w tabeli -->
    <script>
        $(document).ready(function() {
            // Inicjalizacja tabeli DataTables
            $('#workersTable').DataTable();
            
            // Inicjalizacja Tabledit do edycji danych w tabeli
            $('#workersTable').Tabledit({
                url: 'action.php',
                columns: {
                    identifier: [0, 'ID'],
                    editable: [[1, 'IMIE'], [2, 'NAZWISKO'], [3, 'PENSJA']]
                },
                restoreButton: false,
                onSuccess: function(data, textStatus, jqXHR) {
                    if (data.action == 'delete') {
                        $('#' + data.id).remove();
                    }
                }
            });
        });

                // Funkcja wyświetlająca okno edycji pracowników
        function showEditWorkersAlert(animal) {
            // Wyświetlanie okna do edycji oraz umożliwienie jego przeciągania
            $('#showEditWorkers').show().draggable({
                containment: 'parent', // Ograniczenie ruchu okna do obszaru nadrzędnego
                start: function(event, ui) {
                    // Dodanie klasy 'draggable-helper' w momencie rozpoczęcia przeciągania
                    $(this).addClass('draggable-helper');
                },
                drag: function(event, ui) {
                    // Debugowanie: wyświetlanie pozycji okna w konsoli podczas przeciągania
                    console.log('Dragging:', ui.position);
                },
                stop: function(event, ui) {
                    // Usunięcie klasy 'draggable-helper' po zakończeniu przeciągania
                    $(this).removeClass('draggable-helper');
                }
            });

            // Ustawienie pozycji okna na środku ekranu (horyzontalnie i wertykalnie)
            $('#showEditWorkers').css({
                top: '40%', 
                left: '50%', 
                marginTop: -$('#showEditWorkers').outerHeight() / 2, 
                marginLeft: -$('#showEditWorkers').outerWidth() / 2 
            });

            // Przypisanie ID zwierzęcia do elementu z ID 'editKoordynator' 
            $('#editKoordynator').data('animal-id', animal.id); // Przechowywanie ID zwierzęcia w danych elementu
        }

        // Funkcja ukrywająca okno edycji pracowników
        function hideEditWorkersAlert() {
            $('#showEditWorkers').hide(); // Ukrycie okna do edycji
        }

        // Funkcja do wysyłania formularza pracowników na serwer
        function submitWorkersForm() {
            // Zbieranie danych z formularza i tworzenie obiektu
            const data = {
                animalId: $('#editKoordynator').data('animal-id'), 
                koordynator: $('#editKoordynator').val(), 
                opiekun: $('#editOpiekun').val(),
                sprzatacz: $('#editSprzatacz').val(), 
                weterynarz: $('#editWeterynarz').val(), 
            };

            // Debugowanie: sprawdzenie danych przed wysłaniem
            console.log(data);

            // Wyślij dane do serwera za pomocą AJAX
            $.ajax({
                url: 'save_workers.php', 
                type: 'POST', 
                data: data, 
                success: function(response) {
                    // Funkcja wywoływana po udanym zapisie
                    alert('Zapisano zmiany!'); 
                    console.log('Odpowiedź serwera:', response); 
                    hideEditWorkersAlert(); 
                },
                error: function(xhr, status, error) {
                    // Funkcja wywoływana w przypadku błędu
                    console.error('Wystąpił błąd:', error); 
                    alert('Wystąpił błąd podczas zapisywania zmian.');
                }
            });
        }


</script>
</body>
</html>
