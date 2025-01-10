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
         <button class="custom-btn" onclick="showAddDarczyncaAlert()">Dodaj darczyńcę</button>
         <div id="addDarczyncaAlert" class="custom-alert">
            <button class="close-btn" onclick="hideaddDarczyncaAlert()">X</button>
            <h4>Dodaj darczyńcę</h4>
            <h6>Imię:</h6>
            <input type="text" id="newImie" class="form-control mb-2" placeholder="Imię">
            <h6>Nazwisko:</h6>
            <input type="text" id="newNazwisko" class="form-control mb-2" placeholder="Nazwisko">
            <h6>Nazwa użytkownika:</h6>
            <input type="text" id="newNazwa_uzytkownika" class="form-control mb-2" placeholder="Nazwa użytkownika">
            <h6>Email:</h6>
            <input type="text" id="newEmail" class="form-control mb-2" placeholder="Email">
            <button class="btn btn-primary" onclick="submitForm()">Dodaj</button>
         </div>
         <div id="editDarczyncaAlert" class="custom-alert" style="display: none;">
            <form onsubmit="submitEditForm(); return false;">
               <button class="close-btn" type="button" onclick="hideEditDarczyncaAlert()">X</button>
               <h4>Edytuj darczyńcę</h4>
               <input type="hidden" id="editId" class="form-control mb-2">
               <h6>Nazwa użytkownika:</h6>
               <input type="text" id="editNazwa_uzytkownika" class="form-control mb-2" placeholder="Nazwa użytkownika">
               <h6>Imię:</h6>
               <input type="text" id="editImie" class="form-control mb-2" placeholder="Imię">
               <h6>Nazwisko:</h6>
               <input type="text" id="editNazwisko" class="form-control mb-2" placeholder="Nazwisko">
               <h6>Email:</h6>
               <input type="text" id="editEmail" class="form-control mb-2" placeholder="Email">
               <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
            </form>
         </div>
         <h2>Edytowalna Tabela</h2>
         <table id="darczyncaTable" class="table table-bordered">
            <thead>
               <tr>
                  <th>Nazwa użytkownika</th>
                  <th>Imię</th>
                  <th>Nazwisko</th>
                  <th>Email</th>
                  <th>Akcje</th>
               </tr>
            </thead>
            <tbody>
               <?php
                  require_once '../db_connection.php';;
                  try {
                      $sql = "BEGIN :cursor := get_darczyncy; END;";
                      $stid = oci_parse($conn, $sql);
                      $cursor = oci_new_cursor($conn);
                      oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);
                      oci_execute($stid);
                      oci_execute($cursor);
                      while (($row = oci_fetch_assoc($cursor)) != false) {
                          echo "<tr id='" . htmlspecialchars($row['ID']) . "'>";
                          echo "<td>" . htmlspecialchars($row['NAZWA_UZYTKOWNIKA']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['IMIE']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['NAZWISKO']) . "</td>";
                          echo "<td>" . htmlspecialchars($row['MAIL']) . "</td>";
                          $darczyncaData = [
                              "id" => $row['ID'],
                              "nazwa_uzytkownika" => $row['NAZWA_UZYTKOWNIKA'],
                              "imie" => $row['IMIE'],
                              "nazwisko" => $row['NAZWISKO'],
                              "mail" => $row['MAIL'],
                          ];
                          $darczyncaDataJson = htmlspecialchars(json_encode($darczyncaData), ENT_QUOTES, 'UTF-8');
                          echo "<td>
                                  <button onclick='showEditDarczyncaAlert($darczyncaDataJson)' class='btn btn-light'><i class='fa fa-edit'></i></button>
                                  <button class='btn btn-light' onclick='deleteDarczynca(" . htmlspecialchars($row['ID']) . ")'><i class='fa fa-trash'></i></button>
                              </td>";
                          echo "</tr>";
                      }
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
             $('#darczyncaTable').DataTable();
         });

         function showAddDarczyncaAlert() {
             $('#addDarczyncaAlert').show().draggable({
                 containment: 'parent',
             }).css({
                 top: '40%',
                 left: '50%',
                 marginTop: -$('#addDarczyncaAlert').outerHeight() / 2,
                 marginLeft: -$('#addDarczyncaAlert').outerWidth() / 2
             });
         }
         
         function hideaddDarczyncaAlert() {
             $('#addDarczyncaAlert').hide();
         }

         function submitForm() {
            var imie = $('#newImie').val();
            var nazwisko = $('#newNazwisko').val();
            var nazwaUzytkownika = $('#newNazwa_uzytkownika').val();
            var email = $('#newEmail').val();
            $.ajax({
                url: 'add_darczyncy.php',
                type: 'POST',
                data: {
                    imie: imie,
                    nazwisko: nazwisko,
                    nazwa_uzytkownika: nazwaUzytkownika,
                    email: email
                },
                success: function(response) {
                    alert('Darczyńca został dodany!');
                    hideaddDarczyncaAlert();
                    location.reload();
                },
                error: function(error) {
                    alert('Wystąpił błąd: ' + error);
                }
            });
         }

         function deleteDarczynca(darczyncaId) {
            $.ajax({
                url: 'delete_darczyncy.php',
                type: 'POST',
                data: { id: darczyncaId },
                success: function(response) {
                    alert('Darczyńca został usunięty!');
                    location.reload();
                },
                error: function(error) {
                    alert('Wystąpił błąd: ' + error);
                }
            });
         }

         function showEditDarczyncaAlert(darczynca) {
             $('#editId').val(darczynca.id);
             $('#editImie').val(darczynca.imie);
             $('#editNazwisko').val(darczynca.nazwisko);
             $('#editNazwa_uzytkownika').val(darczynca.nazwa_uzytkownika);
             $('#editEmail').val(darczynca.mail);
             $('#editDarczyncaAlert').show().draggable({
                 containment: 'parent',
             }).css({
                 top: '40%',
                 left: '50%',
                 marginTop: -$('#editDarczyncaAlert').outerHeight() / 2,
                 marginLeft: -$('#editDarczyncaAlert').outerWidth() / 2
             });
         }

         function hideEditDarczyncaAlert() {
             $('#editDarczyncaAlert').hide();
         }

         function submitEditForm() {
            var id = $('#editId').val();
            var imie = $('#editImie').val();
            var nazwisko = $('#editNazwisko').val();
            var nazwaUzytkownika = $('#editNazwa_uzytkownika').val();
            var email = $('#editEmail').val();
            $.ajax({
                url: 'edit_darczyncy.php',
                type: 'POST',
                data: {
                    id: id,
                    imie: imie,
                    nazwisko: nazwisko,
                    nazwa_uzytkownika: nazwaUzytkownika,
                    email: email
                },
                success: function(response) {
                    alert('Darczyńca został zaktualizowany!');
                    hideEditDarczyncaAlert();
                    location.reload();
                },
                error: function(error) {
                    alert('Wystąpił błąd: ' + error);
                }
            });
         }
      </script>
   </body>
</html>
