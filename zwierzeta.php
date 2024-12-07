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
         <button class="custom-btn" onclick="showaddAnimalAlert()">Dodaj zwierze</button>
         <div id="addAnimalAlert" class="custom-alert">
            <button class="close-btn" onclick="hideaddAnimalAlert()">X</button>
            <h4>Dodaj zwierze</h4>
            <input type="text" id="newImie" class="form-control mb-2" placeholder="Imię">
            <input type="text" id="newRasa" class="form-control mb-2" placeholder="Rasa">
            <select class="form-control mb-2" id="newPlec">
               <option value="" disabled selected>Wybierz plec</option>
               <option value="Male">Male</option>
               <option value="Female">Female</option>
            </select>
            <select class="form-control mb-2" id="newStatus">
               <option value="" disabled selected>Wybierz status</option>
               <option value="Dostepny">Dostepny</option>
               <option value="Adoptowany">Adoptowany</option>
            </select>
            <div class="input-group mb-2">
               <select class="form-control" id="newKojec">
                  <option value="" disabled selected>Wybierz kojec</option>
                  <?php
                     require_once 'db_connection.php';
                     
                     try {
                         // Przygotowanie wywołania funkcji PL/SQL
                         $sql = "BEGIN :cursor := get_kojce(); END;";
                         $stid = oci_parse($conn, $sql);
                     
                         // Deklaracja kursora jako parametr wyjściowy
                         $cursor = oci_new_cursor($conn);
                         oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);
                     
                         // Wykonanie funkcji PL/SQL
                         oci_execute($stid);
                         oci_execute($cursor);
                     
                         // Pętla do generowania opcji select z wyników kursora
                         while (($row = oci_fetch_assoc($cursor)) != false) {
                             $kojec = $row['KOJEC_ID'] . ', ' . $row['NUMER'];
                             echo "<option value=\"" . htmlspecialchars($row['KOJEC_ID'], ENT_QUOTES, 'UTF-8') . "\">$kojec</option>";
                         }
                     
                         // Zwolnienie zasobów i zamknięcie połączenia
                         oci_free_statement($stid);
                         oci_free_statement($cursor);
                     
                     } catch (Exception $e) {
                         echo "Wystąpił błąd: " . htmlspecialchars($e->getMessage());
                     }
                     ?>
               </select>
            </div>
            <input type="date" id="newData_przyjecia" class="form-control mb-2" placeholder="Data przyjecia">
            <input type="text" id="newWiek" class="form-control mb-2" placeholder="Wiek">
            <input type="text" id="newTyp" class="form-control mb-2" placeholder="Typ">
            <button class="btn btn-primary" onclick="submitForm()">Dodaj</button>
         </div>
         <div id="editAnimalAlert" class="custom-alert" style="display: none;">
            <form onsubmit="submitEditForm(); return false;">
               <button class="close-btn" type="button" onclick="hideEditAnimalAlert()">X</button>
               <h4>Edytuj zwierzaka</h4>
               <input type="hidden" id="editId" class="form-control mb-2">
               <input type="text" id="editImie" class="form-control mb-2" placeholder="Imię">
               <input type="text" id="editRasa" class="form-control mb-2" placeholder="Rasa">
               <select class="form-control mb-2" id="editPlec">
                  <option value="" disabled selected>Wybierz plec</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
               </select>
               <select class="form-control mb-2" id="editStatus">
                  <option value="" disabled selected>Wybierz status</option>
                  <option value="Dostepny">Dostepny</option>
                  <option value="Adoptowany">Adoptowany</option>
               </select>
               <div class="input-group mb-2">
                  <select class="form-control" id="editKojec">
                     <option value="" disabled selected>Wybierz kojec</option>
                     <?php
                        try {
                            // Przygotowanie wywołania funkcji PL/SQL
                            $sql = "BEGIN :cursor := get_kojce(); END;";
                            $stid = oci_parse($conn, $sql);
                        
                            // Deklaracja kursora jako parametr wyjściowy
                            $cursor = oci_new_cursor($conn);
                            oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);
                        
                            // Wykonanie funkcji PL/SQL
                            oci_execute($stid);
                            oci_execute($cursor);
                        
                            // Pętla do generowania opcji select z wyników kursora
                            while (($row = oci_fetch_assoc($cursor)) != false) {
                                $kojec = $row['KOJEC_ID'] . ', ' . $row['NUMER'];
                                echo "<option value=\"" . htmlspecialchars($row['KOJEC_ID'], ENT_QUOTES, 'UTF-8') . "\">$kojec</option>";
                            }
                        
                            // Zwolnienie zasobów i zamknięcie połączenia
                            oci_free_statement($stid);
                            oci_free_statement($cursor);
                        } catch (Exception $e) {
                            echo "Wystąpił błąd: " . htmlspecialchars($e->getMessage());
                        }
                        ?>
                  </select>
               </div>
               <input type="date" id="editData_przyjecia" class="form-control mb-2" placeholder="Data przyjecia">
               <input type="text" id="editWiek" class="form-control mb-2" placeholder="Wiek">
               <input type="text" id="editTyp" class="form-control mb-2" placeholder="Typ">
               <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
            </form>
         </div>
         <h2>Edytowalna Tabela</h2>
         <table id="animalTable" class="table table-bordered">
            <thead>
               <tr>
                  <th>ID</th>
                  <th>Imie</th>
                  <th>Rasa</th>
                  <th>Plec</th>
                  <th>Status</th>
                  <th>Numer kojca</th>
                  <th>Data przyjecia</th>
                  <th>Wiek</th>
                  <th>Typ</th>
                  <th>Akcje</th>
               </tr>
            </thead>
            <tbody>
               <?php
                  try {
                      // Przygotowanie wywołania funkcji PL/SQL
                      $sql = "BEGIN :cursor := get_animals_and_cages(); END;";
                      $stid = oci_parse($conn, $sql);
                  
                      // Deklaracja kursora jako parametr wyjściowy
                      $cursor = oci_new_cursor($conn);
                      oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);
                  
                      // Wykonanie funkcji PL/SQL
                      oci_execute($stid);
                      oci_execute($cursor);
                  
                      // Pętla do generowania wierszy tabeli z wyników kursora
                      while (($row = oci_fetch_assoc($cursor)) != false) {
                          echo "<tr id='" . htmlspecialchars($row['ID'], ENT_QUOTES, 'UTF-8') . "'>";
                          echo "<td>" . htmlspecialchars($row['ID'], ENT_QUOTES, 'UTF-8') . "</td>";
                          echo "<td>" . htmlspecialchars($row['IMIE'], ENT_QUOTES, 'UTF-8') . "</td>";
                          echo "<td>" . htmlspecialchars($row['RASA'], ENT_QUOTES, 'UTF-8') . "</td>";
                          echo "<td>" . htmlspecialchars($row['PLEC'], ENT_QUOTES, 'UTF-8') . "</td>";
                          echo "<td>" . htmlspecialchars($row['STATUS'], ENT_QUOTES, 'UTF-8') . "</td>";
                          echo "<td>" . htmlspecialchars($row['NUMER'], ENT_QUOTES, 'UTF-8') . "</td>";
                          echo "<td>" . htmlspecialchars($row['DATA_PRZYJECIA'], ENT_QUOTES, 'UTF-8') . "</td>";
                          echo "<td>" . htmlspecialchars($row['WIEK'], ENT_QUOTES, 'UTF-8') . "</td>";
                          echo "<td>" . htmlspecialchars($row['TYP'], ENT_QUOTES, 'UTF-8') . "</td>";
                          
                          // Przygotowanie danych dla JavaScript
                          $animalData = [
                              "id" => $row['ID'],
                              "imie" => $row['IMIE'],
                              "rasa" => $row['RASA'],
                              "plec" => $row['PLEC'],
                              "status" => $row['STATUS'],
                              "numer" => $row['NUMER'],
                              "data_przyjecia" => $row['DATA_PRZYJECIA'],
                              "wiek" => $row['WIEK'],
                              "typ" => $row['TYP']
                          ];
                          $animalDataJson = htmlspecialchars(json_encode($animalData), ENT_QUOTES, 'UTF-8');
                      
                          echo "<td>
                              <button onclick='showEditAnimalAlert($animalDataJson)' class='btn btn-light'><i class='fa fa-edit'></i></button>
                              <button class='btn btn-light' onclick='deleteAnimal(" . htmlspecialchars($row['ID'], ENT_QUOTES, 'UTF-8') . ")'><i class='fa fa-trash'></i></button>
                              <button onclick='' class='btn btn-light'><i class='fa fa-user'></i></button>
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
         $(document).ready(function(){ 
             $('#animalTable').DataTable();
             $('#animalTable').Tabledit({ 
                 url: 'action.php',
                 columns: { 
                     identifier: [0, 'ID'], 
                     editable: [[1, 'IMIE'], [2, 'RASA'], [3, 'PLEC'], [4, 'STATUS'], [5, 'NUMER'], [6, 'DATA_PRZYJECIA'], [7, 'WIEK'], [8, 'TYP']]
                 }, 
                 restoreButton: false, 
                 onSuccess: function(data, textStatus, jqXHR) { 
                     if(data.action == 'delete') { 
                         $('#' + data.id).remove();
                      } 
                     }
                  }); 
                 }); 
                 
                 function showaddAnimalAlert() {
                 $('#addAnimalAlert').show().draggable({
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
                 $('#addAnimalAlert').css({
                     top: '40%',
                     left: '50%',
                     marginTop: -$('#addAnimalAlert').outerHeight() / 2,
                     marginLeft: -$('#addAnimalAlert').outerWidth() / 2
                 });
             }
         
         
         
         
         function hideaddAnimalAlert() {
             $('#addAnimalAlert').hide();
         }
         
         function submitForm() {
             // Pobranie wartości z pól formularza
             var imie = $('#newImie').val();
             var rasa = $('#newRasa').val();
             var plec = $('#newPlec').val();
             var status = $('#newStatus').val();
             var numer_kojca = $('#newKojec').val();
             var data_przyjecia = $('#newData_przyjecia').val();
             var wiek = $('#newWiek').val();
             var typ = $('#newTyp').val();
         
         // Wysłanie danych do skryptu PHP za pomocą AJAX
         $.ajax({
             url: 'add_animal.php', // Skrypt PHP obsługujący dodawanie pracownika
             type: 'POST',
             data: {
                 imie: imie,
                 rasa: rasa,
                 plec: plec,
                 status: status,
                 numer_kojca: numer_kojca,
                 data_przyjecia: data_przyjecia,
                 wiek: wiek,
                 typ: typ
             },
             dataType: 'json', // Oczekujemy odpowiedzi w formacie JSON
             success: function(response) {
                 if (response.success) {
                     // Akcje po pomyślnym dodaniu pracownika (np. odświeżenie tabeli, ukrycie alertu)
                     alert('Zwierzak został dodany!');
                     hideaddAnimalAlert();
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
         
         function deleteAnimal(animalId) {
         // Wysłanie danych do skryptu PHP za pomocą AJAX
         $.ajax({
         url: 'delete_animal.php', // Skrypt PHP obsługujący usuwanie pracownika
         type: 'POST',
         data: {
             id: animalId
         },
         dataType: 'json', // Oczekujemy odpowiedzi w formacie JSON
         success: function(response) {
             if (response.success) {
                 // Akcje po pomyślnym usunięciu pracownika (np. odświeżenie tabeli)
                 alert('Zwierzak został usunięty!');
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
         
         
         function showEditAnimalAlert(animal) {
         $('#editId').val(animal.id);
         $('#editImie').val(animal.imie);
         $('#editRasa').val(animal.rasa);
         $('#editPlec').val(animal.plec);
         $('#editStatus').val(animal.status);
         $('#editKojec').val(animal.numer_kojca);
         $('#editData_przyjecia').val(animal.data_przyjecia);
         $('#editWiek').val(animal.wiek);
         $('#editTyp').val(animal.typ);
         
         $('#editAnimalAlert').show().draggable({
         containment: 'parent',
         }).css({
         top: '40%',
         left: '50%',
         marginTop: -$('#editAnimalAlert').outerHeight() / 2,
         marginLeft: -$('##editAnimalAlert').outerWidth() / 2
         });
         }
         
         function hideEditAnimalAlert() {
         $('#editAnimalAlert').hide();
         }
         
         function submitEditForm() {
         var id = $('#editId').val();
         var imie = $('#editImie').val();
         var rasa = $('#editRasa').val();
         var plec = $('#editPlec').val();
         var status = $('#editStatus').val();
         var numer_kojca = $('#editKojec').val();
         var data_przyjecia = $('#editData_przyjecia').val();
         var wiek = $('#editWiek').val();
         var typ = $('#editTyp').val();
         
         $.ajax({
         url: 'edit_animal.php', // Skrypt PHP obsługujący edycję
         type: 'POST',
         data: {
                 id: id,
                 imie: imie,
                 rasa: rasa,
                 plec: plec,
                 status: status,
                 numer_kojca: numer_kojca,
                 data_przyjecia: data_przyjecia,
                 wiek: wiek,
                 typ: typ
         },
         dataType: 'json',
         success: function(response) {
             if (response.success) {
                 alert('Zwierzak został zaktualizowany!');
                 hideEditAnimalAlert();
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
   </body>
</html>