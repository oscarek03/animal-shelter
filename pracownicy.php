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
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
   </head>
   <body>
      <div class="container">
         <button class="custom-btn" onclick="showAddEmployeeAlert()">Dodaj pracownika</button>
         <div id="addEmployeeAlert" class="custom-alert">
            <button class="close-btn" onclick="hideAddEmployeeAlert()">X</button>
            <h4>Dodaj pracownika</h4>
            <h6>Imię:</h6>
            <input type="text" id="newImie" class="form-control mb-2" placeholder="Imię">
            <h6>Nazwisko:</h6>
            <input type="text" id="newNazwisko" class="form-control mb-2" placeholder="Nazwisko">
            <h6>Pensja:</h6>
            <input type="number" id="newPensja" class="form-control mb-2" placeholder="Pensja">
            <h6>Stanowisko:</h6>
            <select class="form-control mb-2" id="newStanowisko">
               <option value="" disabled selected>Wybierz stanowisko</option>
               <option value="Koordynator Adopcji" >Koordynator Adopcji</option>
               <option value="Sprzątacz" >Sprzątacz</option>
               <option value="Opiekun Zwierząt" >Opiekun Zwierząt</option>
               <option value="Weteryniarz" >Weteryniarz</option>
            </select>
            <h6>Adres:</h6>
            <div class="input-group mb-2">
               <select class="form-control" id="newAdres">
                  <option value="" disabled selected>Wybierz adres</option>
                  <?php
                     require_once 'db_connection.php';
                     
                     try {
                         // Wywołanie funkcji PL/SQL i przypisanie zwróconego kursora
                         $sql = "BEGIN :cursor := get_adresy(); END;";
                         $stid = oci_parse($conn, $sql);
                     
                         // Deklaracja kursora jako parametr wyjściowy
                         $cursor = oci_new_cursor($conn);
                         oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);
                     
                         // Wykonanie funkcji PL/SQL
                         oci_execute($stid);
                         oci_execute($cursor);
                     
                         // Iteracja przez wyniki zwrócone przez kursor i generowanie opcji do select
                         while (($row = oci_fetch_assoc($cursor)) != false) {
                             $adres = htmlspecialchars($row['MIASTO']) . ', ' . 
                                     htmlspecialchars($row['KOD_POCZTOWY']) . ', ' . 
                                     htmlspecialchars($row['ULICA']) . ' ' . 
                                     htmlspecialchars($row['NUMER_DOMU']) . 
                                     (!empty($row['NUMER_MIESZKANIA']) ? '/' . htmlspecialchars($row['NUMER_MIESZKANIA']) : '');
                                     
                             echo "<option value=\"" . htmlspecialchars($row['ID_ADRESU']) . "\">$adres</option>";
                         }
                     
                         // Zwolnienie zasobów
                         oci_free_statement($stid);
                         oci_free_statement($cursor);
                     
                     } catch (Exception $e) {
                         echo "Wystąpił błąd: " . htmlspecialchars($e->getMessage());
                     }
                     ?>
               </select>
               <button class="btn btn-plus" onclick="showAddAddressAlert()">+</button>
            </div>
            <h6>Data zatrudnienia:</h6>
            <input type="date" id="newDataZatrudnienia" class="form-control mb-2" placeholder="Data Zatrudnienia">
            <button class="btn btn-primary" onclick="submitForm()">Dodaj</button>
         </div>
         <div id="editEmployeeAlert" class="custom-alert" style="display: none;">
            <form onsubmit="submitEditForm(); return false;">
               <button class="close-btn" type="button" onclick="hideEditEmployeeAlert()">X</button>
               <h4>Edytuj pracownika</h4>
               <input type="hidden" id="editId" class="form-control mb-2">
               <h6>Imie:</h6>
               <input type="text" id="editImie" class="form-control mb-2" placeholder="Imię">
               <h6>Nazwisko:</h6>
               <input type="text" id="editNazwisko" class="form-control mb-2" placeholder="Nazwisko">
               <h6>Pensja:</h6>
               <input type="number" id="editPensja" class="form-control mb-2" placeholder="Pensja">
               <h6>Stanowisko:</h6>
               <select id="editStanowisko" class="form-control mb-2">
                  <option value="" disabled selected>Wybierz stanowisko</option>
                  <option value="Koordynator adopcji">Koordynator Adopcji</option>
                  <option value="Sprzątacz">Sprzątacz</option>
                  <option value="Opiekun Zwierząt">Opiekun Zwierząt</option>
                  <option value="Weteryniarz">Weteryniarz</option>
               </select>
               <h6>Adres:</h6>
               <div class="input-group mb-2">
                  <select class="form-control" id="editAdres">
                     <!-- Opcje załadują się dynamicznie -->
                     <option value="" disabled selected>Wybierz adres</option>
                     <?php
                        try {
                            // Wywołanie funkcji PL/SQL i przypisanie zwróconego kursora
                            $sql = "BEGIN :cursor := get_formatted_addresses(); END;";
                            $stid = oci_parse($conn, $sql);
                        
                            // Deklaracja kursora jako parametr wyjściowy
                            $cursor = oci_new_cursor($conn);
                            oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);
                        
                            // Wykonanie funkcji PL/SQL
                            oci_execute($stid);
                            oci_execute($cursor);
                        
                            // Iteracja przez wyniki zwrócone przez kursor i generowanie opcji do select
                            while (($row = oci_fetch_assoc($cursor)) != false) {
                                echo "<option value=\"" . htmlspecialchars($row['ID_ADRESU']) . "\">" . 
                                    htmlspecialchars($row['FULL_ADDRESS']) . 
                                    "</option>";
                            }
                        
                            // Zwolnienie zasobów
                            oci_free_statement($stid);
                            oci_free_statement($cursor);
                        
                        } catch (Exception $e) {
                            echo "Wystąpił błąd: " . htmlspecialchars($e->getMessage());
                        }
                        ?>
                  </select>
                  <button class="btn btn-plus" type="button" onclick="showAddAddressAlert()">+</button>
               </div>
               <h6>Data zatrudnienia:</h6>
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
                  <th>Data zatrudnienia</th>
                  <th>Akcje</th>
               </tr>
            </thead>
            <tbody>
               <?php
                  try {
                      // Wywołanie funkcji PL/SQL i przypisanie zwróconego kursora
                      $sql = "BEGIN :cursor := get_pracownicy_with_address(); END;";
                      $stid = oci_parse($conn, $sql);
                  
                      // Deklaracja kursora jako parametr wyjściowy
                      $cursor = oci_new_cursor($conn);
                      oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);
                  
                      // Wykonanie funkcji PL/SQL
                      oci_execute($stid);
                      oci_execute($cursor);
                  
                      // Iteracja przez wyniki zwrócone przez kursor
                      while (($row = oci_fetch_assoc($cursor)) != false) {
                          echo "<tr id='" . htmlspecialchars($row['ID']) . "'>";
                          echo "<td>" . htmlspecialchars($row['IMIE']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['NAZWISKO']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['PENSJA']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['STANOWISKO']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['FULL_ADDRESS']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['DATA_ZATRUDNIENIA']) . "</td>";
                  
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
                                  <button onclick='showEditEmployeeAlert($employeeDataJson)' class='btn btn-light'><i class='fa fa-edit'></i></button>
                                  <button class='btn btn-light' onclick='deleteEmployee(" . htmlspecialchars($row['ID']) . ")'><i class='fa fa-trash'></i></button>
                              </td>";
                          echo "</tr>";
                      }
                  
                      // Zwolnienie zasobów
                      oci_free_statement($stid);
                      oci_free_statement($cursor);
                      oci_close($conn);
                  
                  } catch (Exception $e) {
                      echo "Wystąpił błąd: " . $e->getMessage();
                  }
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