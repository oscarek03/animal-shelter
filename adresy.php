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
        <div id="editAddressAlert" class="custom-alert" style="display: none;">
            <form onsubmit="submitEditAddressForm(); return false;">
                <button class="close-btn" type="button" onclick="hideEditAddressAlert()">X</button>
                <h4>Edytuj adres</h4>
                <input type="hidden" id="editAddressId" class="form-control mb-2">
                <input type="text" id="editMiasto" class="form-control mb-2" placeholder="Miasto" required>
                <input type="text" id="editKodPocztowy" class="form-control mb-2" placeholder="Kod pocztowy" required>
                <input type="text" id="editUlica" class="form-control mb-2" placeholder="Ulica">
                <input type="text" id="editNumerDomu" class="form-control mb-2" placeholder="Numer domu">
                <input type="text" id="editNumerMieszkania" class="form-control mb-2" placeholder="Numer mieszkania">
                <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
            </form>
        </div>

        <h2>Adresy</h2>
        <table id="addressTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Miasto</th>
                    <th>Kod pocztowy</th>
                    <th>Ulica</th>
                    <th>Numer domu</th>
                    <th>Numer mieszkania</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php
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
                $sql = "SELECT 
                ID_ADRESU, 
                MIASTO, 
                KOD_POCZTOWY, 
                ULICA, 
                NUMER_DOMU, 
                NUMER_MIESZKANIA 
            FROM Adresy";
    
            
                $stid = oci_parse($conn, $sql);
                oci_execute($stid);

                while (($row = oci_fetch_assoc($stid)) != false) {
                    echo "<tr id='" . $row['ID_ADRESU'] . "'>";
                    echo "<td>" . $row['MIASTO'] . "</td>";
                    echo "<td>" . $row['KOD_POCZTOWY'] . "</td>";
                    echo "<td>" . $row['ULICA'] . "</td>";
                    echo "<td>" . $row['NUMER_DOMU'] . "</td>";
                    echo "<td>" . $row['NUMER_MIESZKANIA'] . "</td>";
                    echo "<td><button onclick=\"showEditAddressAlert(" . $row['ID_ADRESU'] . ")\" class=\"btn btn-warning\">Edytuj</button><br/><button class='btn btn-danger' onclick='deleteAddress(" . $row['ID_ADRESU'] . ")'>Usuń</button></td>";
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
            $('#addressTable').DataTable();
            $('#addressTable').Tabledit({ 
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

                function deleteAddress(adresId) {
    // Wysłanie danych do skryptu PHP za pomocą AJAX
    $.ajax({
        url: 'delete_address.php', // Skrypt PHP obsługujący usuwanie adresu
        type: 'POST',
        data: {
            id: adresId
        },
        dataType: 'json', // Oczekujemy odpowiedzi w formacie JSON
        success: function(response) {
            if (response.success) {
                // Akcje po pomyślnym usunięciu adresu (np. odświeżenie tabeli)
                alert('Adres został usunięty!');
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

function showEditAddressAlert(adresId) {
    // Pobranie danych z tabeli
    var row = $("#" + adresId);
    $("#editAddressId").val(adresId);
    $("#editMiasto").val(row.find("td:eq(0)").text());
    $("#editKodPocztowy").val(row.find("td:eq(1)").text());
    $("#editUlica").val(row.find("td:eq(2)").text());
    $("#editNumerDomu").val(row.find("td:eq(3)").text());
    $("#editNumerMieszkania").val(row.find("td:eq(4)").text());

    // Wyświetlenie formularza
    $("#editAddressAlert").show();
}

function hideEditAddressAlert() {
    $("#editAddressAlert").hide();
}

function submitEditAddressForm() {
    // Pobieranie danych z formularza
    var idAdresu = $("#editAddressId").val();
    var miasto = $("#editMiasto").val();
    var kodPocztowy = $("#editKodPocztowy").val();
    var ulica = $("#editUlica").val();
    var numerDomu = $("#editNumerDomu").val();
    var numerMieszkania = $("#editNumerMieszkania").val();

    // Wysłanie danych do edycji adresu za pomocą AJAX
    $.ajax({
        url: 'edit_address.php', // Plik obsługujący procedurę edytującą adres
        type: 'POST',
        data: {
            id_adresu: idAdresu,
            miasto: miasto,
            kod_pocztowy: kodPocztowy,
            ulica: ulica,
            numer_domu: numerDomu,
            numer_mieszkania: numerMieszkania
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert('Adres został zaktualizowany!');
                hideEditAddressAlert();
                location.reload(); // Odświeżenie strony
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
    <button class="custom-btn" onclick="showAddAddressAlert()">Dodaj Adres</button>

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
