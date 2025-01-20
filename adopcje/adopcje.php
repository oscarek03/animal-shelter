<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Adopcje</title>
      <link rel="stylesheet" href="../styles/style1.css">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
      <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
   </head>
   <body>
      <div class="container">
         <button class="custom-btn" onclick="showAddAdoptionAlert()">Dodaj Adopcję</button>
         <div id="addAdoptionAlert" class="custom-alert">
            <button class="close-btn" onclick="hideAddAdoptionAlert()">X</button>
            <h4>Dodaj Adopcję</h4>
            <!-- Select dla zwierząt -->
            <h6>Zwierze:</h6>
            <select id="newZwierze" class="form-control mb-2">
               <option value="" disabled selected>Wybierz zwierzę</option>
               <?php
               require_once "../db_connection.php";

               try {
                   // Przygotowanie wywołania funkcji PL/SQL
                   $sql = "BEGIN :cursor := get_available_animals(); END;";
                   $stid = oci_parse($conn, $sql);

                   // Deklaracja kursora jako parametr wyjściowy
                   $cursor = oci_new_cursor($conn);
                   oci_bind_by_name(
                       $stid,
                       ":cursor",
                       $cursor,
                       -1,
                       OCI_B_CURSOR
                   );

                   // Wykonanie funkcji PL/SQL
                   oci_execute($stid);
                   oci_execute($cursor);

                   // Iteracja przez wyniki zwrócone przez kursor i generowanie opcji do select
                   while (($row = oci_fetch_assoc($cursor)) != false) {
                       echo "<option value=\"" .
                           htmlspecialchars($row["ID"]) .
                           "\">" .
                           htmlspecialchars($row["IMIE"]) .
                           " (" .
                           htmlspecialchars($row["RASA"]) .
                           ") - " .
                           htmlspecialchars($row["NUMER"]) .
                           "</option>";
                   }

                   // Zwolnienie zasobów i zamknięcie połączenia
                   oci_free_statement($stid);
                   oci_free_statement($cursor);
               } catch (Exception $e) {
                   echo "Wystąpił błąd: " . htmlspecialchars($e->getMessage());
               }
               ?>
            </select>
            <!-- Select dla pracowników -->
            <h6>Koordynator adopcji:</h6>
            <select id="newPracownik" class="form-control mb-2">
               <option value="" disabled selected>Wybierz pracownika</option>
               <?php
               // Wywołanie funkcji PL/SQL
               $sql = "BEGIN :cursor := get_koordynatorzy_adopcji(); END;";
               $stid = oci_parse($conn, $sql);

               // Deklaracja kursora
               $cursor = oci_new_cursor($conn);

               // Przypisanie zmiennej kursora do wyjścia
               oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);

               // Wykonanie procedury PL/SQL
               oci_execute($stid);
               oci_execute($cursor); // Uruchomienie kursora

               // Iteracja wyników
               while (($row = oci_fetch_assoc($cursor)) != false) {
                   echo "<option value=\"" .
                       $row["ID"] .
                       "\">" .
                       $row["IMIE"] .
                       " " .
                       $row["NAZWISKO"] .
                       "</option>";
               }

               // Zwolnienie zasobów
               oci_free_statement($stid);
               oci_free_statement($cursor);
               ?>
            </select>
            <!-- Select dla adresów -->
            <h6>Adres adoptujacego:</h6>
            <div class="input-group mb-2">
               <select class="form-control" id="newAdres">
                  <option value="" disabled selected>Wybierz adres</option>
                  <?php try {
                      // Przygotowanie wywołania funkcji PL/SQL
                      $sql = "BEGIN :cursor := get_adresy(); END;";
                      $stid = oci_parse($conn, $sql);

                      // Deklaracja kursora jako parametr wyjściowy
                      $cursor = oci_new_cursor($conn);
                      oci_bind_by_name(
                          $stid,
                          ":cursor",
                          $cursor,
                          -1,
                          OCI_B_CURSOR
                      );

                      // Wykonanie funkcji PL/SQL
                      oci_execute($stid);
                      oci_execute($cursor);

                      // Iteracja przez wyniki zwrócone przez kursor i generowanie opcji do select
                      while (($row = oci_fetch_assoc($cursor)) != false) {
                          $adres = htmlspecialchars(
                              $row["MIASTO"] .
                                  ", " .
                                  $row["KOD_POCZTOWY"] .
                                  ", " .
                                  $row["ULICA"] .
                                  " " .
                                  $row["NUMER_DOMU"] .
                                  (isset($row["NUMER_MIESZKANIA"])
                                      ? "/" . $row["NUMER_MIESZKANIA"]
                                      : ""),
                              ENT_QUOTES,
                              "UTF-8"
                          );

                          echo "<option value=\"" .
                              htmlspecialchars($row["ID_ADRESU"]) .
                              "\">$adres</option>";
                      }

                      // Zwolnienie zasobów i zamknięcie połączenia
                      oci_free_statement($stid);
                      oci_free_statement($cursor);
                  } catch (Exception $e) {
                      echo "Wystąpił błąd: " .
                          htmlspecialchars($e->getMessage());
                  } ?>
               </select>
               <button class="btn btn-plus" onclick="showAddAddressAlert()">+</button>
            </div>
            <h6>Imie adoptujacego:</h6>
            <input type="text" id="newImie" class="form-control mb-2" placeholder="Imię" required>
            <h6>Nazwisko adoptujacego:</h6>
            <input type="text" id="newNazwisko" class="form-control mb-2" placeholder="Nazwisko" required>
            <h6>Telefon adoptujacego:</h6>
            <input type="tel" id="newTelefon" class="form-control mb-2" placeholder="Numer telefonu" required>
            <!-- Data adopcji -->
            <h6>Data adopcji:</h6>
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
               <?php try {
                   // Przygotowanie wywołania funkcji PL/SQL
                   $sql = "BEGIN :cursor := get_adoption_details(); END;";
                   $stid = oci_parse($conn, $sql);

                   // Deklaracja kursora jako parametr wyjściowy
                   $cursor = oci_new_cursor($conn);
                   oci_bind_by_name(
                       $stid,
                       ":cursor",
                       $cursor,
                       -1,
                       OCI_B_CURSOR
                   );

                   // Wykonanie funkcji PL/SQL
                   oci_execute($stid);
                   oci_execute($cursor);

                   // Iteracja przez wyniki zwrócone przez kursor i generowanie wierszy w tabeli
                   while (($row = oci_fetch_assoc($cursor)) != false) {
                       echo "<tr>";
                       echo "<td>" .
                           htmlspecialchars(
                               $row["ZWIERZE"],
                               ENT_QUOTES,
                               "UTF-8"
                           ) .
                           "</td>";
                       echo "<td>" .
                           htmlspecialchars(
                               $row["PRACOWNIK"],
                               ENT_QUOTES,
                               "UTF-8"
                           ) .
                           "</td>";
                       echo "<td>" .
                           htmlspecialchars(
                               $row["ADRES"],
                               ENT_QUOTES,
                               "UTF-8"
                           ) .
                           "</td>";
                       echo "<td>" .
                           htmlspecialchars(
                               $row["DATA_ADOPCJI"],
                               ENT_QUOTES,
                               "UTF-8"
                           ) .
                           "</td>";
                       echo "<td>" .
                           htmlspecialchars(
                               $row["DANE_ADOPTUJACEGO"],
                               ENT_QUOTES,
                               "UTF-8"
                           ) .
                           "</td>";
                       echo "<td><button class='btn btn-light' onclick='deleteAdoption(" .
                           $row["ID_ADOPCJI"] .
                           ")'><i class='fa fa-trash'></i></button></td>";
                       echo "</tr>";
                   }

                   // Zwolnienie zasobów i zamknięcie połączenia
                   oci_free_statement($stid);
                   oci_free_statement($cursor);
                   oci_close($conn);
               } catch (Exception $e) {
                   echo "Wystąpił błąd: " . htmlspecialchars($e->getMessage());
               } ?>
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