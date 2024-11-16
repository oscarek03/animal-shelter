<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://markcell.github.io/jquery-tabledit/jquery.tabledit.min.js"></script>
</head>
<body>
    <div class="container">
        <button class="custom-btn" onclick="showAddEmployeeAlert()">Dodaj pracownika</button>
        <div id="addEmployeeAlert" class="custom-alert">
            <button class="close-btn" onclick="hideAddEmployeeAlert()">X</button>
            <h4>Dodaj pracownika</h4>
            <input type="text" id="newImie" class="form-control mb-2" placeholder="Imię">
            <input type="text" id="newNazwisko" class="form-control mb-2" placeholder="Nazwisko">
            <input type="number" id="newPensja" class="form-control mb-2" placeholder="Pensja">
            <select class="form-control mb-2" id="newStanowisko">
                <option value="" disabled selected>Wybierz stanowisko</option>
                <option value="Koordynator adopcji" >Koordynator adopcji</option>
                <option value="Sprzątacz" >Sprzątacz</option>
                <option value="Opiekun Zwierząt" >Opiekun Zwierząt</option>
                <option value="Weteryniarz" >Weteryniarz</option> </select>
            <div class="input-group mb-2">
                <select class="form-control" id="newAdres">
                    <option value="" disabled selected>Wybierz adres</option>
                    <?php
                    putenv('NLS_LANG=AMERICAN_AMERICA.UTF8');
                    $host = "127.0.0.1";
                    $port = "1521";
                    $service_name = "XEPDB1";
                    $dsn = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port))(CONNECT_DATA=(SERVICE_NAME=$service_name)))";
                    $username = "schronisko";
                    $password = "123";
                    // Połącz z bazą danych
                    $conn = oci_connect($username, $password, $dsn);
                    if (!$conn) {
                        $e = oci_error();
                        die("Połączenie nieudane: " . $e['message']);
                    }

                    // Pobierz dane z tabeli ADRESY
                    $sql = "SELECT ID_ADRESU, MIASTO, KOD_POCZTOWY, ULICA, NUMER_DOMU, NUMER_MIESZKANIA FROM Adresy";
                    $stid = oci_parse($conn, $sql);
                    oci_execute($stid);

                    // Wstaw opcje do select
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
            <input type="date" id="newDataZatrudnienia" class="form-control mb-2" placeholder="Data Zatrudnienia">
            <button class="btn btn-primary" onclick="submitForm()">Dodaj</button>
        </div>


        <div id="editEmployeeAlert" class="custom-alert" style="display: none;">
    <form onsubmit="submitEditForm(); return false;">
        <button class="close-btn" type="button" onclick="hideEditEmployeeAlert()">X</button>
        <h4>Edytuj pracownika</h4>
        <input type="hidden" id="editId" class="form-control mb-2">
        <input type="text" id="editImie" class="form-control mb-2" placeholder="Imię">
        <input type="text" id="editNazwisko" class="form-control mb-2" placeholder="Nazwisko">
        <input type="number" id="editPensja" class="form-control mb-2" placeholder="Pensja">
        <select id="editStanowisko" class="form-control mb-2">
            <option value="" disabled selected>Wybierz stanowisko</option>
            <option value="Koordynator adopcji">Koordynator adopcji</option>
            <option value="Sprzątacz">Sprzątacz</option>
            <option value="Opiekun Zwierząt">Opiekun Zwierząt</option>
            <option value="Weteryniarz">Weteryniarz</option>
        </select>
        <div class="input-group mb-2">
            <select class="form-control" id="editAdres">
                <!-- Opcje załadują się dynamicznie -->
                <option value="" disabled selected>Wybierz adres</option>
                <?php
                putenv('NLS_LANG=AMERICAN_AMERICA.UTF8');
                // Adresy z bazy danych
                $host = "127.0.0.1";
                $port = "1521";
                $service_name = "XEPDB1";
                $dsn = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port))(CONNECT_DATA=(SERVICE_NAME=$service_name)))";
                $username = "schronisko";
                $password = "123";
                $conn = oci_connect($username, $password, $dsn);
                if (!$conn) {
                    $e = oci_error();
                    die("Połączenie nieudane: " . $e['message']);
                }
                $sql = "SELECT ID_ADRESU, MIASTO || ', ' || KOD_POCZTOWY || ', ' || ULICA || ' ' || NUMER_DOMU AS FULL_ADDRESS FROM Adresy";
                $stid = oci_parse($conn, $sql);
                oci_execute($stid);
                while (($row = oci_fetch_assoc($stid)) != false) {
                    echo "<option value=\"" . $row['ID_ADRESU'] . "\">" . $row['FULL_ADDRESS'] . "</option>";
                }
                oci_free_statement($stid);
                oci_close($conn);
                ?>
            </select>
            <button type="button" class="btn btn-plus" onclick="showAddAddressAlert()">+</button>
        </div>
        <input type="date" id="editDataZatrudnienia" class="form-control mb-2" placeholder="Data Zatrudnienia">
        <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
    </form>
</div>


        <h2>Edytowalna Tabela</h2>
        <table id="employeeTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>Wynagrodzenie</th>
                    <th>Stanowisko</th>
                    <th>Adres</th>
                    <th>DATA ZATRUDNIENIA</th>
                    <th>AKCJE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                putenv('NLS_LANG=AMERICAN_AMERICA.UTF8');
                $host = "127.0.0.1";
                $port = "1521";
                $service_name = "XEPDB1";
                $dsn = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port))(CONNECT_DATA=(SERVICE_NAME=$service_name)))";
                $username = "schronisko";
                $password = "123";
                $conn = oci_connect($username, $password, $dsn);
                if (!$conn) {
                    $e = oci_error();
                    die("Połączenie nieudane: " . $e['message']);
                }

                // Modyfikujemy zapytanie, aby połączyć tabele i pobrać pełny adres.
                $sql = "SELECT p.ID, p.IMIE, p.NAZWISKO, p.PENSJA, p.STANOWISKO, 
                a.ULICA || ' ' || a.NUMER_DOMU || COALESCE('/' || a.NUMER_MIESZKANIA, '') || ', ' || a.MIASTO || ', ' || a.KOD_POCZTOWY AS FULL_ADDRESS, 
                p.DATA_ZATRUDNIENIA
                FROM Pracownicy p
                LEFT JOIN Adresy a ON p.ADRES_ID = a.ID_ADRESU";
            
                $stid = oci_parse($conn, $sql);
                oci_execute($stid);

                while (($row = oci_fetch_assoc($stid)) != false) {
                    echo "<tr id='" . $row['ID'] . "'>";
                    echo "<td>" . $row['IMIE'] . "</td>";
                    echo "<td>" . $row['NAZWISKO'] . "</td>";
                    echo "<td>" . $row['PENSJA'] . "</td>";
                    echo "<td>" . $row['STANOWISKO'] . "</td>";
                    echo "<td>" . $row['FULL_ADDRESS'] . "</td>";
                    echo "<td>" . $row['DATA_ZATRUDNIENIA'] . "</td>";
                    
                    // Przygotowanie danych dla JavaScript
                    $employeeData = [
                        "id" => $row['ID'],
                        "imie" => $row['IMIE'],
                        "nazwisko" => $row['NAZWISKO'],
                        "pensja" => $row['PENSJA'],
                        "stanowisko" => $row['STANOWISKO'],
                        "adresId" => $row['FULL_ADDRESS'],
                        "dataZatrudnienia" => $row['DATA_ZATRUDNIENIA']
                    ];
                    $employeeDataJson = htmlspecialchars(json_encode($employeeData), ENT_QUOTES, 'UTF-8');
                
                    echo "<td>
                        <button onclick='showEditEmployeeAlert($employeeDataJson)' class='btn btn-warning'>Edytuj</button>
                        <br/>
                        <button class='btn btn-danger' onclick='deleteEmployee(" . $row['ID'] . ")'>Usuń</button>
                    </td>";
                    
                    echo "</tr>";
                }
                
                
                oci_free_statement($stid);
                oci_close($conn);
                ?>
            </tbody>
        </table>
    </div>







    <script>
        $(document).ready(function(){ 
            $('#employeeTable').DataTable();
            $('#employeeTable').Tabledit({ 
                url: 'action.php',
                columns: { 
                    identifier: [0, 'ID'], 
                    editable: [[1, 'IMIE'], [2, 'NAZWISKO'], [3, 'PENSJA']] 
                }, 
                restoreButton: false, 
                onSuccess: function(data, textStatus, jqXHR) { 
                    if(data.action == 'delete') { 
                        $('#' + data.id).remove();
                     } 
                    }
                 }); 
                }); 
                function showAddEmployeeAlert() {
                $('#addEmployeeAlert').show().draggable({
                    containment: 'parent', // Ograniczenie ruchu do elementu nadrzędnego
                    start: function(event, ui) {
                        $(this).addClass('draggable-helper');
                    },
                    drag: function(event, ui) {
                        // Nic do dodania tutaj, tylko debugowanie
                        console.log('Dragging:', ui.position);
                    },
                    stop: function(event, ui) {
                        $(this).removeClass('draggable-helper');
                    }
                });
                $('#addEmployeeAlert').css({
                    top: '40%',
                    left: '50%',
                    marginTop: -$('#addEmployeeAlert').outerHeight() / 2,
                    marginLeft: -$('#addEmployeeAlert').outerWidth() / 2
                });
            }




        function hideAddEmployeeAlert() {
            $('#addEmployeeAlert').hide();
        }

        function submitForm() {
    // Pobranie wartości z pól formularza
    var imie = $('#newImie').val();
    var nazwisko = $('#newNazwisko').val();
    var pensja = $('#newPensja').val();
    var stanowisko = $('#newStanowisko').val();
    var adresId = $('#newAdres').val();
    var dataZatrudnienia = $('#newDataZatrudnienia').val();

    // Wysłanie danych do skryptu PHP za pomocą AJAX
        $.ajax({
            url: 'add_employee.php', // Skrypt PHP obsługujący dodawanie pracownika
            type: 'POST',
            data: {
                imie: imie,
                nazwisko: nazwisko,
                pensja: pensja,
                stanowisko: stanowisko,
                adres_id: adresId,
                data_zatrudnienia: dataZatrudnienia
            },
            dataType: 'json', // Oczekujemy odpowiedzi w formacie JSON
            success: function(response) {
                if (response.success) {
                    // Akcje po pomyślnym dodaniu pracownika (np. odświeżenie tabeli, ukrycie alertu)
                    alert('Pracownik został dodany!');
                    hideAddEmployeeAlert();
                    location.reload(); // Odświeżenie strony w celu aktualizacji tabeli
                } else {
                    // Wyświetlenie komunikatu o błędzie
                    alert('Wystąpił błąd: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                // Akcje w przypadku błędu
                alert('Wystąpił błąd: ' + error);
            }
        });
    }

    function deleteEmployee(pracownikId) {
    // Wysłanie danych do skryptu PHP za pomocą AJAX
    $.ajax({
        url: 'delete_employee.php', // Skrypt PHP obsługujący usuwanie pracownika
        type: 'POST',
        data: {
            id: pracownikId
        },
        dataType: 'json', // Oczekujemy odpowiedzi w formacie JSON
        success: function(response) {
            if (response.success) {
                // Akcje po pomyślnym usunięciu pracownika (np. odświeżenie tabeli)
                alert('Pracownik został usunięty!');
                location.reload(); // Odświeżenie strony w celu aktualizacji tabeli
            } else {
                // Wyświetlenie komunikatu o błędzie
                alert('Wystąpił błąd: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            // Akcje w przypadku błędu
            alert('Wystąpił błąd: ' + error);
        }
    });
}

function showAddAddressAlert() {
    $('#addAddressAlert').show().draggable({
        containment: 'parent', // Ograniczenie ruchu do elementu nadrzędnego
        start: function(event, ui) {
            $(this).addClass('draggable-helper');
        },
        stop: function(event, ui) {
            $(this).removeClass('draggable-helper');
        }
    });
}

function hideAddAddressAlert() {
    $('#addAddressAlert').hide();
}

function submitAddressForm() {
    // Pobranie wartości z pól formularza
    var miasto = $('#newMiasto').val();
    var kodPocztowy = $('#newKodPocztowy').val();
    var ulica = $('#newUlica').val();
    var numerDomu = $('#newNumerDomu').val();
    var numerMieszkania = $('#newNumerMieszkania').val();

    // Wysłanie danych do skryptu PHP za pomocą AJAX
    $.ajax({
        url: 'add_address.php', // Skrypt PHP obsługujący dodawanie adresu
        type: 'POST',
        data: {
            miasto: miasto,
            kod_pocztowy: kodPocztowy,
            ulica: ulica,
            numer_domu: numerDomu,
            numer_mieszkania: numerMieszkania
        },
        dataType: 'json', // Oczekujemy odpowiedzi w formacie JSON
        success: function(response) {
            if (response.success) {
                alert('Adres został dodany!');
                hideAddAddressAlert();
                location.reload(); // Odświeżenie strony w celu aktualizacji tabeli
            } else {
                alert('Wystąpił błąd: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            alert('Wystąpił błąd: ' + error);
        }
    });
}


function showEditEmployeeAlert(employee) {
    $('#editId').val(employee.id);
    $('#editImie').val(employee.imie);
    $('#editNazwisko').val(employee.nazwisko);
    $('#editPensja').val(employee.pensja);
    $('#editStanowisko').val(employee.stanowisko);
    $('#editAdres').val(employee.adresId);
    $('#editDataZatrudnienia').val(employee.dataZatrudnienia);

    $('#editEmployeeAlert').show().draggable({
        containment: 'parent',
    }).css({
        top: '40%',
        left: '50%',
        marginTop: -$('#editEmployeeAlert').outerHeight() / 2,
        marginLeft: -$('#editEmployeeAlert').outerWidth() / 2
    });
}

function hideEditEmployeeAlert() {
    $('#editEmployeeAlert').hide();
}

function submitEditForm() {
    var id = $('#editId').val();
    var imie = $('#editImie').val();
    var nazwisko = $('#editNazwisko').val();
    var pensja = $('#editPensja').val();
    var stanowisko = $('#editStanowisko').val();
    var adresId = $('#editAdres').val();
    var dataZatrudnienia = $('#editDataZatrudnienia').val();

    $.ajax({
        url: 'edit_employee.php', // Skrypt PHP obsługujący edycję
        type: 'POST',
        data: {
            id: id,
            imie: imie,
            nazwisko: nazwisko,
            pensja: pensja,
            stanowisko: stanowisko,
            adres_id: adresId,
            data_zatrudnienia: dataZatrudnienia
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert('Pracownik został zaktualizowany!');
                hideEditEmployeeAlert();
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


    </script>

    <br><br><br><br>
    <div id="addAddressAlert" class="custom-alert">
        <form onsubmit="submitAddressForm(); return false;">
            <button class="close-btn" type="button" onclick="hideAddAddressAlert()">X</button>
            <h4>Dodaj Adres</h4>
            <input type="text" id="newMiasto" class="form-control mb-2" placeholder="Miasto" required>
            <input type="text" id="newKodPocztowy" class="form-control mb-2" placeholder="Kod Pocztowy" required>
            <input type="text" id="newUlica" class="form-control mb-2" placeholder="Ulica" >
            <input type="text" id="newNumerDomu" class="form-control mb-2" placeholder="Numer Domu">
            <input type="text" id="newNumerMieszkania" class="form-control mb-2" placeholder="Numer Mieszkania">
            <button class="btn btn-primary" type="submit">Dodaj</button>
        </form>
    </div>

</body>
</html>





