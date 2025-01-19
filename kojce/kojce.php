<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Document</title>
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
      <div class="container">
         <button class="custom-btn" onclick="showaddKojecAlert()">Dodaj kojec</button>


         <div id="addKojecAlert" class="custom-alert">
            <button class="close-btn" onclick="hideaddKojecAlert()">X</button>
            <h4>Dodaj kojec</h4>
            <h6>Numer kojca:</h6>
            <input type="text" id="newNumer" class="form-control mb-2" placeholder="Numer kojca">
            <h6>Rozmiar kojca:</h6>
            <select class="form-control mb-2" id="newRozmiar">
               <option value="" disabled selected>Wybierz rozmiar</option>
               <option value="Maly">Maly</option>
               <option value="Sredni">Sredni</option>
               <option value="Duzy">Duzy</option>
            </select>
            <button class="btn btn-primary" onclick="submitForm()">Dodaj</button>
         </div>


         <div id="editKojecAlert" class="custom-alert" style="display: none;">
            <form onsubmit="submitEditForm(); return false;">
               <button class="close-btn" type="button" onclick="hideeditKojecAlert()">X</button>
               <h4>Edytuj kojec</h4>
               <input type="hidden" id="editId" class="form-control mb-2">
            <h6>Numer kojca:</h6>
               <input type="text" id="editNumer" class="form-control mb-2" placeholder="Numer kojca">
            <h6>Rozmiar kojca:</h6>
               <select class="form-control mb-2" id="editRozmiar">
                  <option value="" disabled selected>Wybierz rozmiar:</option>
                  <option value="Maly">Maly</option>
                  <option value="Sredni">Sredni</option>
                  <option value="Duzy">Duzy</option>
               </select>
               <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
            </form>
         </div>

         <h2>Edytowalna Tabela</h2>
         <table id="KojecTable" class="table table-bordered">
            <thead>
               <tr>
                  <th>Numer kojca</th>
                  <th>Rozmiar kojca</th>
                  <th>Akcje</th>
               </tr>
            </thead>
            <tbody>
               <?php
                    require_once '../db_connection.php';
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
                    
                        // Pętla do generowania wierszy tabeli z wyników kursora
                        while (($row = oci_fetch_assoc($cursor)) != false) {
                            echo "<tr id='" . htmlspecialchars($row['KOJEC_ID'], ENT_QUOTES, 'UTF-8') . "'>";
                            echo "<td>" . htmlspecialchars($row['NUMER'], ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td>" . htmlspecialchars($row['WIELKOSC'], ENT_QUOTES, 'UTF-8') . "</td>";
                            
                            // Przygotowanie danych dla JavaScript
                            $kojecData = [
                                "id" => $row['KOJEC_ID'],
                                "imie" => $row['NUMER'],
                                "rasa" => $row['WIELKOSC']
                            ];
                            $kojecDataJson = htmlspecialchars(json_encode($kojecData), ENT_QUOTES, 'UTF-8');
                        
                            echo "<td>
                                <button onclick='showeditKojecAlert($kojecDataJson)' class='btn btn-light'><i class='fa fa-edit'></i></button>
                                <button class='btn btn-light' onclick='deleteKojec(" . htmlspecialchars($row['KOJEC_ID'], ENT_QUOTES, 'UTF-8') . ")'><i class='fa fa-trash'></i></button>
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
             $('#KojecTable').DataTable();
             $('#KojecTable').Tabledit({ 
                 url: 'action.php',
                 restoreButton: false, 
                  }); 
                 }); 
                 
                 function showaddKojecAlert() {
                 $('#addKojecAlert').show().draggable({
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
                 $('#addKojecAlert').css({
                     top: '40%',
                     left: '50%',
                     marginTop: -$('#addKojecAlert').outerHeight() / 2,
                     marginLeft: -$('#addKojecAlert').outerWidth() / 2
                 });
             }
         // Funkcja chowajaca okno dodawania kojca
        function hideaddKojecAlert() {
             $('#addKojecAlert').hide();
         }
        // Funkcja chowajaca okno edycji kojca
        function hideEditWorkersAlert() {
            $('#showEditWorkers').hide();
        }


         
        // Funkcja wysyłająca dane nowego kojca do serwera
        function submitForm() {
            var numer = $('#newNumer').val();
            var rozmiar = $('#newRozmiar').val();

            // Walidacja
            if (!numer || !rozmiar) {
                alert('Wypełnij wszystkie pola!');
                return;
            }

            // Wysłanie danych do serwera za pomocą AJAX
            $.ajax({
                url: 'add_kojec.php',
                type: 'POST',
                data: {
                    numer: numer,
                    rozmiar: rozmiar
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Kojec został dodany!');
                        hideaddKojecAlert();
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
        // Funkcja do usuwania kojca
        function deleteKojec(kojecId) {
            if (!kojecId) {
                alert('Brak ID kojca do usunięcia!');
                return;
            }

            $.ajax({
                url: 'delete_kojec.php',
                type: 'POST',
                data: { id: kojecId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Kojec został usunięty!');
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

         
        // Funkcja wyświetlająca okno edycji kojca
        function showeditKojecAlert(kojec) {
            $('#editId').val(kojec.id);
            $('#editNumer').val(kojec.imie); // Zakładam, że kojec.imie to numer
            $('#editRozmiar').val(kojec.rasa); // Zakładam, że kojec.rasa to rozmiar

            $('#editKojecAlert').show().draggable({
                containment: 'parent',
            }).css({
                top: '40%',
                left: '50%',
                marginTop: -$('#editKojecAlert').outerHeight() / 2,
                marginLeft: -$('#editKojecAlert').outerWidth() / 2
            });
        }

         
         function hideeditKojecAlert() {
         $('#editKojecAlert').hide();
         }
         
         function submitEditForm() {
            var id = $('#editId').val();
            var numer = $('#editNumer').val();
            var rozmiar = $('#editRozmiar').val();

            // Walidacja
            if (!numer || !rozmiar) {
                alert('Wypełnij wszystkie pola!');
                return;
            }

            // Wysłanie danych do serwera za pomocą AJAX
            $.ajax({
                url: 'edit_kojec.php',
                type: 'POST',
                data: {
                    kojec_id: id,       // Poprawione
                    numer_kojca: numer, // Poprawione
                    rozmiar_kojca: rozmiar // Poprawione
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Kojec został zaktualizowany!');
                        hideeditKojecAlert();
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