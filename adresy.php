<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <!-- Linki do stylów CSS i bibliotek -->
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
    <button class="custom-btn" onclick="showAddAddressAlert()">Dodaj Adres</button>

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

        <!-- Nagłówek strony -->
        <h2>Adresy</h2>
        
        <!-- Tabela z adresami -->
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
                require_once 'db_connection.php';

                try {
                    // Przygotowanie wywołania funkcji PL/SQL
                    $sql = "BEGIN :cursor := get_addresses(); END;";
                    $stid = oci_parse($conn, $sql);

                    // Deklaracja kursora jako parametr wyjściowy
                    $cursor = oci_new_cursor($conn);
                    oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);

                    // Wykonanie funkcji PL/SQL
                    oci_execute($stid);
                    oci_execute($cursor);

                    // Iteracja przez wyniki zwrócone przez kursor i generowanie wierszy w tabeli
                    while (($row = oci_fetch_assoc($cursor)) != false) {
                        echo "<tr id='" . htmlspecialchars($row['ID_ADRESU'], ENT_QUOTES, 'UTF-8') . "'>";
                        echo "<td>" . htmlspecialchars($row['MIASTO'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['KOD_POCZTOWY'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['ULICA'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['NUMER_DOMU'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['NUMER_MIESZKANIA'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>
                                <button onclick=\"showEditAddressAlert(" . htmlspecialchars($row['ID_ADRESU'], ENT_QUOTES, 'UTF-8') . ")\" class=\"btn btn-warning\">Edytuj</button>
                                <br/>
                                <button class='btn btn-danger' onclick='deleteAddress(" . htmlspecialchars($row['ID_ADRESU'], ENT_QUOTES, 'UTF-8') . ")'>Usuń</button>
                            </td>";
                        echo "</tr>";
                    }

                    // Zwolnienie zasobów i zamknięcie połączenia
                    oci_free_statement($stid);
                    oci_free_statement($cursor);
                    oci_close($conn);

                } catch (Exception $e) {
                    echo "Wystąpił błąd: " . htmlspecialchars($e->getMessage());
                }
            ?>

            </tbody>
        </table>
    </div>

    <script>
        // Inicjalizacja DataTable oraz Tabledit dla tabeli
        $(document).ready(function() {
            $('#addressTable').DataTable();
            $('#addressTable').Tabledit({
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

        // Funkcja do usuwania adresu z użyciem AJAX
        function deleteAddress(adresId) {
            $.ajax({
                url: 'delete_address.php', // Skrypt PHP obsługujący usuwanie adresu
                type: 'POST',
                data: {
                    id: adresId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Adres został usunięty!');
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


function showAddAddressAlert() {
                $('#addAddressAlert').show().draggable({
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
                $('#addAddressAlert').css({
                    top: '40%',
                    left: '50%',
                    marginTop: -$('#addAddressAlert').outerHeight() / 2,
                    marginLeft: -$('#addAddressAlert').outerWidth() / 2
                });
            }

        function hideAddAddressAlert() {
            $('#addAddressAlert').hide();
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

        function showEditAddressAlert(adresId) {
            var row = $("#" + adresId);
            $("#editAddressId").val(adresId);
            $("#editMiasto").val(row.find("td:eq(0)").text());
            $("#editKodPocztowy").val(row.find("td:eq(1)").text());
            $("#editUlica").val(row.find("td:eq(2)").text());
            $("#editNumerDomu").val(row.find("td:eq(3)").text());
            $("#editNumerMieszkania").val(row.find("td:eq(4)").text());

    // Wyświetlenie formularza
    $('#editAddressAlert').show().draggable({
        containment: 'parent',
    }).css({
        top: '40%',
        left: '50%',
        marginTop: -$('#editAddressAlert').outerHeight() / 2,
        marginLeft: -$('##editAddressAlert').outerWidth() / 2
    });
}

        function hideEditAddressAlert() {
            $("#editAddressAlert").hide();
        }

        function submitEditAddressForm() {
            var adresId = $("#editAddressId").val();
            var miasto = $("#editMiasto").val();
            var kodPocztowy = $("#editKodPocztowy").val();
            var ulica = $("#editUlica").val();
            var numerDomu = $("#editNumerDomu").val();
            var numerMieszkania = $("#editNumerMieszkania").val();

            $.ajax({
                url: 'edit_address.php',
                type: 'POST',
                data: {
                    id: adresId,
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
