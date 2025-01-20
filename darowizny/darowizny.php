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
         <button class="custom-btn" onclick="showAddDarowiznaAlert()">Dodaj darowiznę</button>
         <div id="addDarowiznaAlert" class="custom-alert">
            <button class="close-btn" onclick="hideAddDarowiznaAlert()">X</button>
            <h4>Dodaj darowiznę</h4>

            <!-- Wybór darczyńcy -->
            <h6>Wybierz darczyńcę:</h6>
            <select id="newDarczyncaId" class="form-control mb-2">
                <option value="">Wybierz...</option>
                <?php
                    require_once "../db_connection.php";

                    try {
                        // Pobranie listy darczyńców
                        $sql = "SELECT ID, NAZWA_UZYTKOWNIKA FROM DARCZYNCY";
                        $stid = oci_parse($conn, $sql);
                        oci_execute($stid);

                        // Dodanie opcji do selecta
                        while (($row = oci_fetch_assoc($stid)) != false) {
                            echo "<option value='" .
                                htmlspecialchars($row["ID"]) .
                                "'>" .
                                htmlspecialchars($row["NAZWA_UZYTKOWNIKA"]) .
                                "</option>";
                        }

                        oci_free_statement($stid);
                    } catch (Exception $e) {
                        echo "<option value=''>Błąd wczytywania danych</option>";
                    }
                ?>
            </select>

    <!-- Kwota -->
    <h6>Kwota:</h6>
    <input type="text" id="newKwota" class="form-control mb-2" placeholder="Kwota">

    <!-- Data -->
    <h6>Data:</h6>
    <input type="date" id="newData" class="form-control mb-2">

    <!-- Przycisk dodania -->
    <button class="btn btn-primary" onclick="submitForm()">Dodaj</button>
</div>

<div id="editDarowiznaAlert" class="custom-alert" style="display: none;">
    <form onsubmit="submitEditForm(); return false;">
        <button class="close-btn" type="button" onclick="hideEditDarowiznaAlert()">X</button>
        <h4>Edytuj darowiznę</h4>
        <input type="hidden" id="editId" class="form-control mb-2">

        <!-- Wybór darczyńcy -->
        <h6>Wybierz darczyńcę:</h6>
        <select id="editDarczyncaId" class="form-control mb-2">
            <option value="">Wybierz...</option>
            <?php 
                try {
                    // Pobranie listy darczyńców
                    $sql = "SELECT ID, NAZWA_UZYTKOWNIKA FROM DARCZYNCY";
                    $stid = oci_parse($conn, $sql);
                    oci_execute($stid);

                    // Dodanie opcji do selecta
                    while (($row = oci_fetch_assoc($stid)) != false) {
                        echo "<option value='" .
                            htmlspecialchars($row["ID"]) .
                            "'>" .
                            htmlspecialchars($row["NAZWA_UZYTKOWNIKA"]) .
                            "</option>";
                    }

                    oci_free_statement($stid);
                } catch (Exception $e) {
                    echo "<option value=''>Błąd wczytywania danych</option>";
                } 
            ?>
        </select>

        <!-- Kwota -->
        <h6>Kwota:</h6>
        <input type="text" id="editKwota" class="form-control mb-2" placeholder="Kwota">

        <!-- Data -->
        <h6>Data:</h6>
        <input type="date" id="editData" class="form-control mb-2">

        <!-- Przycisk zapisania -->
        <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
    </form>
</div>

         <h2>Edytowalna Tabela Darowizn</h2>
         <table id="darowiznaTable" class="table table-bordered">
            <thead>
               <tr>
                  <th>Nazwa użytkownika</th>
                  <th>Kwota</th>
                  <th>Data</th>
                  <th>Akcje</th>
               </tr>
            </thead>
            <tbody>
               <?php 
                    try {
                        $sql = "BEGIN :cursor := GET_DAROWIZNY; END;";
                        $stid = oci_parse($conn, $sql);
                        $cursor = oci_new_cursor($conn);
                        oci_bind_by_name(
                            $stid,
                            ":cursor",
                            $cursor,
                            -1,
                            OCI_B_CURSOR
                        );
                        oci_execute($stid);
                        oci_execute($cursor);
                        while (($row = oci_fetch_assoc($cursor)) != false) {
                            echo "<tr id='" . htmlspecialchars($row["ID"]) . "'>";
                            echo "<td>" .
                                htmlspecialchars($row["NAZWA_UZYTKOWNIKA"]) .
                                "</td>";
                            echo "<td>" . htmlspecialchars($row["KWOTA"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["DATA"]) . "</td>";
                            $darowiznaData = [
                                "id" => $row["ID"],
                                "kwota" => $row["KWOTA"],
                                "data" => $row["DATA"],
                                "nazwa_uzytkownika" => $row["NAZWA_UZYTKOWNIKA"],
                            ];
                            $darowiznaDataJson = htmlspecialchars(
                                json_encode($darowiznaData),
                                ENT_QUOTES,
                                "UTF-8"
                            );
                            echo "<td>
                                        <button onclick='showEditDarowiznaAlert($darowiznaDataJson)' class='btn btn-light'><i class='fa fa-edit'></i></button>
                                        <button class='btn btn-light' onclick='deleteDarowizna(" .
                                htmlspecialchars($row["ID"]) .
                                ")'><i class='fa fa-trash'></i></button>
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
             $('#darowiznaTable').DataTable();
         });

         function showAddDarowiznaAlert() {
             $('#addDarowiznaAlert').show().draggable({
                 containment: 'parent',
             }).css({
                 top: '40%',
                 left: '50%',
                 marginTop: -$('#addDarowiznaAlert').outerHeight() / 2,
                 marginLeft: -$('#addDarowiznaAlert').outerWidth() / 2
             });
         }
         
         function hideAddDarowiznaAlert() {
             $('#addDarowiznaAlert').hide();
         }

         function submitForm() {
            var darczynca_id = $('#newDarczyncaId').val();
            var kwota = $('#newKwota').val();
            var data = $('#newData').val();
            $.ajax({
                url: 'add_darowizny.php',
                type: 'POST',
                data: {
                    darczynca_id: darczynca_id, // Dodano brakujące pole
                    kwota: kwota,
                    data: data
                },
                success: function(response) {
                    alert('Darowizna została dodana!');
                    hideAddDarowiznaAlert();
                    location.reload();
                },
                error: function(error) {
                    alert('Wystąpił błąd: ' + error.responseText);
                }
            });
        }


         function deleteDarowizna(darowiznaId) {
            $.ajax({
                url: 'delete_darowizny.php',
                type: 'POST',
                data: { id: darowiznaId },
                success: function(response) {
                    alert('Darowizna została usunięta!');
                    location.reload();
                },
                error: function(error) {
                    alert('Wystąpił błąd: ' + error);
                }
            });
         }

         function showEditDarowiznaAlert(darowizna) {
             $('#editId').val(darowizna.id);
             $('#editKwota').val(darowizna.kwota);
             $('#editData').val(darowizna.data);
             $('#editDarowiznaAlert').show().draggable({
                 containment: 'parent',
             }).css({
                 top: '40%',
                 left: '50%',
                 marginTop: -$('#editDarowiznaAlert').outerHeight() / 2,
                 marginLeft: -$('#editDarowiznaAlert').outerWidth() / 2
             });
         }

         function hideEditDarowiznaAlert() {
             $('#editDarowiznaAlert').hide();
         }

         function submitEditForm() {
            var id = $('#editId').val();
            var kwota = $('#editKwota').val();
            var darczynca_id = $('#editDarczyncaId').val();
            var data = $('#editData').val();
            $.ajax({
                url: 'edit_darowizny.php',
                type: 'POST',
                data: {
                    id: id,
                    darczynca_id: darczynca_id,
                    kwota: kwota,
                    data: data
                },
                success: function(response) {
                    alert('Darowizna została zaktualizowana!');
                    hideEditDarowiznaAlert();
                    location.reload();
                },
                error: function(xhr, status, error) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        alert('Wystąpił błąd: ' + response.message);
                    } catch (e) {
                        alert('Wystąpił błąd: ' + error);
                    }
                }

            });
         }
      </script>
   </body>
</html>