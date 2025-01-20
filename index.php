<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vertical Menu with Bootstrap</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      height: 100vh;
      display: flex;
    }
    .sidebar {
      height: 100vh;
      overflow-y: auto;
      border-right: 1px solid #ddd;
    }
    .nav-link {
      font-size: 18px;
      font-weight: bold;
      color: #000;
      padding: 12px 20px;
    }
    .nav-link:hover {
      background-color: #f8f9fa;
    }
    .nav-item:not(:last-child) {
      border-bottom: 1px solid #ddd;
    }
    .main-content {
      flex-grow: 1;
    }
    tr:nth-child(even) {
      background-color: #f8f9fa;
    }
  </style>
</head>
<body>
  <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <div class="position-sticky">
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link active" href="./pracownicy/pracownicy.php">Pracownicy</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./adresy/adresy.php">Adresy</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="zwierzeta/zwierzeta.php">Zwierzeta</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="adopcje/adopcje.php">Adopcje</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="pracownicy-zwierzeta/pracownicy-zwierzeta.php">Pracownicy - Zwierzęta</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="darowizny/darowizny.php">Darowizny</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="darczyncy/darczyncy.php">Darczyncy</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="kojce/kojce.php">Kojce</a>
        </li>
      </ul>
    </div>
  </nav>

  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">Witaj na stronie!</h1>
    </div>
    <h4>Wybierz jedną z opcji z menu po lewej stronie, aby przejść do odpowiedniej sekcji.</h4>
      <br>
      <br>
      <div class="row">
    <!-- Kafelek 1 -->
    <div class="col-md-6 mb-4">
      <div class="card">
          <div class="card-body">
          <?php
            require_once 'db_connection.php';

            try {
                // Przygotowanie wywołania funkcji PL/SQL
                $sql = "BEGIN :cursor := get_avg_donation(); END;";
                $stid = oci_parse($conn, $sql);

                // Deklaracja kursora jako parametr wyjściowy
                $cursor = oci_new_cursor($conn);
                oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);

                // Wykonanie funkcji PL/SQL
                oci_execute($stid);
                oci_execute($cursor);

                // Pobranie wyniku z kursora
                $row = oci_fetch_assoc($cursor);

                if ($row) {
                    echo "<h5>Średnia wartość darowizny:</h5> <p>" . htmlspecialchars($row['AVG(KWOTA)']) . " zł</p>";
                } else {
                    echo "<p>Nie znaleziono wyników.</p>";
                }

                // Zwolnienie zasobów i zamknięcie połączenia
                oci_free_statement($stid);
                oci_free_statement($cursor);

            } catch (Exception $e) {
                echo "Wystąpił błąd: " . htmlspecialchars($e->getMessage());
            }
          ?>
        </div>
      </div>
    </div>
    <!-- Kafelek 2 -->
    <div class="col-md-6 mb-4">
      <div class="card">
          <div class="card-body">
          <?php

            try {
                $sql = "BEGIN :cursor := get_avg_salary(); END;";
                $stid = oci_parse($conn, $sql);

                $cursor = oci_new_cursor($conn);
                oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);

                oci_execute($stid);
                oci_execute($cursor);

                $row = oci_fetch_assoc($cursor);

                if ($row) {
                    echo "<h5>Średnia wartość pensji: </h5><p>" . htmlspecialchars($row['AVG(PENSJA)']) . " zł</p>";
                } else {
                    echo "<p>Nie znaleziono wyników.</p>";
                }

                oci_free_statement($stid);
                oci_free_statement($cursor);

            } catch (Exception $e) {
                echo "Wystąpił błąd: " . htmlspecialchars($e->getMessage());
            }
          ?>
        </div>
      </div>
    </div>
    <!-- Kafelek 3 -->
    <div class="col-md-6 mb-4">
      <div class="card">
          <div class="card-body">
          <?php
            try {
                $sql = "BEGIN :cursor := get_sum_donations(); END;";
                $stid = oci_parse($conn, $sql);

                $cursor = oci_new_cursor($conn);
                oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);

                oci_execute($stid);
                oci_execute($cursor);

                $row = oci_fetch_assoc($cursor);

                if ($row) {
                    echo "<h5>Łączna wartość darowizn: </h5><p>" . htmlspecialchars($row['SUM(KWOTA)']) . " zł</p>";
                } else {
                    echo "<p>Nie znaleziono wyników.</p>";
                }

                oci_free_statement($stid);
                oci_free_statement($cursor);

            } catch (Exception $e) {
                echo "Wystąpił błąd: " . htmlspecialchars($e->getMessage());
            }
          ?>
        </div>
      </div>
    </div>
    <!-- Kafelek 4 -->
    <div class="col-md-6 mb-4">
      <div class="card">
          <div class="card-body">
          <?php
            try {
                $sql = "BEGIN :cursor := get_sum_salaries(); END;";
                $stid = oci_parse($conn, $sql);

                $cursor = oci_new_cursor($conn);
                oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);

                oci_execute($stid);
                oci_execute($cursor);

                $row = oci_fetch_assoc($cursor);

                if ($row) {
                    echo "<h5>Łączna wartość pensji: </h5><p>" . htmlspecialchars($row['SUM(PENSJA)']) . " zł </p>";
                } else {
                    echo "<p>Nie znaleziono wyników.</p>";
                }

                oci_free_statement($stid);
                oci_free_statement($cursor);

            } catch (Exception $e) {
                echo "Wystąpił błąd: " . htmlspecialchars($e->getMessage());
            }
            ?>
        </div>
      </div>
    </div>
    <!-- Kafelek 5 -->
    <div class="col-md-12 mb-12">
      <div class="card">
          <div class="card-body">
      <h5>Statystki Koordynatorów:</h5>
      <?php
            try {
                $sql = "BEGIN :cursor := get_adoption_coordinators_stats(); END;";
                $stid = oci_parse($conn, $sql);

                $cursor = oci_new_cursor($conn);
                oci_bind_by_name($stid, ":cursor", $cursor, -1, OCI_B_CURSOR);

                oci_execute($stid);
                oci_execute($cursor);

                echo "<table style='width:100%;'>";
                echo "<tr style='text-align:center;'><th>Imię</th><th>Nazwisko</th><th>Liczba zwierząt</th></tr>";

                while (($row = oci_fetch_assoc($cursor)) != false) {
                    echo "<tr style='text-align:center;'>";
                    echo "<td>" . htmlspecialchars($row['IMIE']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['NAZWISKO']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['LICZBA_ZWIERZAT']) . "</td>";
                    echo "</tr>";
                }

                echo "</table>";

                oci_free_statement($stid);
                oci_free_statement($cursor);

            } catch (Exception $e) {
                echo "Wystąpił błąd: " . htmlspecialchars($e->getMessage());
            }
          ?>
        </div>
      </div>
    </div>
    <p></p>
    <!-- Kafelek 6 -->
    <div class="col-md-6 mb-4">
      <div class="card">
          <div class="card-body">
          <?php
            try {
                $sql = "BEGIN :result := COUNT_MIXED_BREED_DOGS(); END;";
                $stid = oci_parse($conn, $sql);

                $result = 0;
                oci_bind_by_name($stid, ":result", $result, -1, OCI_B_INT);

                oci_execute($stid);

                echo "<h5>Liczba psów (Mieszanców): </h5><p>" . htmlspecialchars($result) . "</p>";

                oci_free_statement($stid);

            } catch (Exception $e) {
                echo "Wystąpił błąd: " . htmlspecialchars($e->getMessage());
            }
          ?>
        </div>
      </div>
    </div>
    <!-- Kafelek 7 -->
    <div class="col-md-6 mb-4">
      <div class="card">
          <div class="card-body">
          <?php
            try {
                $sql = "BEGIN :result := COUNT_NON_MIXED_BREED_DOGS(); END;";
                $stid = oci_parse($conn, $sql);

                $result = 0;
                oci_bind_by_name($stid, ":result", $result, -1, OCI_B_INT);

                oci_execute($stid);

                echo "<h5>Liczba psów (Rasowych): </h5><p>" . htmlspecialchars($result) . "</p>";

                oci_free_statement($stid);

            } catch (Exception $e) {
                echo "Wystąpił błąd: " . htmlspecialchars($e->getMessage());
            }
          ?>
        </div>
      </div>
    </div>
    <!-- Kafelek 8 -->
    <div class="col-md-6 mb-4">
      <div class="card">
          <div class="card-body">
          <?php
            try {
                $sql = "BEGIN :result := COUNT_MIXED_BREED_CATS(); END;";
                $stid = oci_parse($conn, $sql);

                $result = 0;
                oci_bind_by_name($stid, ":result", $result, -1, OCI_B_INT);

                oci_execute($stid);

                echo "<h5>Liczba kotów (Mieszanców): </h5><p>" . htmlspecialchars($result) . "</p>";

                oci_free_statement($stid);

            } catch (Exception $e) {
                echo "Wystąpił błąd: " . htmlspecialchars($e->getMessage());
            }
          ?>
        </div>
      </div>
    </div>
    <!-- Kafelek 9 -->
    <div class="col-md-6 mb-4">
      <div class="card">
          <div class="card-body">
          <?php
            try {
                $sql = "BEGIN :result := COUNT_NON_MIXED_BREED_CATS(); END;";
                $stid = oci_parse($conn, $sql);

                $result = 0;
                oci_bind_by_name($stid, ":result", $result, -1, OCI_B_INT);

                oci_execute($stid);

                echo "<h5>Liczba kotów (Rasowych): </h5><p>" . htmlspecialchars($result) . "</p>";

                oci_free_statement($stid);

            } catch (Exception $e) {
                echo "Wystąpił błąd: " . htmlspecialchars($e->getMessage());
            }
          ?>
        </div>
      </div>
    </div>
  </main>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
