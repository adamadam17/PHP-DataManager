<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Najvyššie budovy</title>
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    a {
        text-decoration: none;
        color: blue;
    }
</style>
<SCRIPT LANGUAGE="JavaScript">
function confirmBox(data) {
    if (window.confirm("Ste si istý, že chcete zmazať túto budovu?")) {
        self.location.href = "delete.php?id_budova=" + data;
    }
}
</SCRIPT>
</head>
<body>

<?php

$zorad = isset($_GET['zorad']) ? $_GET['zorad'] : 1;
$architekt_filter = isset($_GET['architekt']) ? $_GET['architekt'] : '';
$narodnost_filter = isset($_GET['narodnost']) ? $_GET['narodnost'] : '';


require('connect.php');


if (!$spojenie) {
    echo "Spojenie s databázou neprebehlo!";
    exit;
}


$sql_query = "
    SELECT b.id, b.nazov, b.lokalita, b.vyska, b.pocet_poschodi, b.rok_dokoncenia, 
           a.meno, a.narodnost 
    FROM x_richtarik_Budovy b
    LEFT JOIN x_richtarik_Architekti a ON b.id = a.budova_id
    WHERE 1 = 1
";

// Filtrovanie podľa architekta
if (!empty($architekt_filter)) {
    $sql_query .= " AND a.meno = '" . mysqli_real_escape_string($spojenie, $architekt_filter) . "'";
}

// Filtrovanie podľa národnosti
if (!empty($narodnost_filter)) {
    $sql_query .= " AND a.narodnost = '" . mysqli_real_escape_string($spojenie, $narodnost_filter) . "'";
}

// Zoradenie
switch ($zorad) {
    case 1:
        $sql_query .= " ORDER BY b.nazov ASC";
        break;
    case 2:
        $sql_query .= " ORDER BY b.nazov DESC";
        break;
    case 3:
        $sql_query .= " ORDER BY b.vyska ASC";
        break;
    case 4:
        $sql_query .= " ORDER BY b.vyska DESC";
        break;
    case 5:
        $sql_query .= " ORDER BY b.rok_dokoncenia ASC";
        break;
    case 6:
        $sql_query .= " ORDER BY b.rok_dokoncenia DESC";
        break;
}


$sql = mysqli_query($spojenie, $sql_query);


if (!$sql) {
    echo "Nepodarilo sa vytvoriť SQL dotaz!";
    exit;
}


$architekti = mysqli_query($spojenie, "SELECT DISTINCT meno FROM x_richtarik_Architekti");
$narodnosti = mysqli_query($spojenie, "SELECT DISTINCT narodnost FROM x_richtarik_Architekti");
?>

<h1>Najvyššie budovy sveta</h1>


<form method="GET" action="index.php">
    <label for="architekt">Filter podľa architekta:</label>
    <select name="architekt" id="architekt">
        <option value="">-- Všetci architekti --</option>
        <?php while ($row = mysqli_fetch_assoc($architekti)): ?>
            <option value="<?php echo $row['meno']; ?>" <?php echo $architekt_filter == $row['meno'] ? 'selected' : ''; ?>>
                <?php echo $row['meno']; ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label for="narodnost">Filter podľa národnosti:</label>
    <select name="narodnost" id="narodnost">
        <option value="">-- Všetky národnosti --</option>
        <?php while ($row = mysqli_fetch_assoc($narodnosti)): ?>
            <option value="<?php echo $row['narodnost']; ?>" <?php echo $narodnost_filter == $row['narodnost'] ? 'selected' : ''; ?>>
                <?php echo $row['narodnost']; ?>
            </option>
        <?php endwhile; ?>
    </select>

    <button type="submit">Filtrovať</button>
</form>


<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Názov <a href="index.php?zorad=1">↑</a> <a href="index.php?zorad=2">↓</a></th>
            <th>Lokalita</th>
            <th>Výška (m) <a href="index.php?zorad=3">↑</a> <a href="index.php?zorad=4">↓</a></th>
            <th>Počet poschodí</th>
            <th>Rok dokončenia <a href="index.php?zorad=5">↑</a> <a href="index.php?zorad=6">↓</a></th>
            <th>Architekt</th>
            <th>Národnosť architekta</th>
            <th>Akcie</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($sql)) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['nazov']}</td>
                <td>{$row['lokalita']}</td>
                <td>{$row['vyska']}</td>
                <td>{$row['pocet_poschodi']}</td>
                <td>{$row['rok_dokoncenia']}</td>
                <td>{$row['meno']}</td>
                <td>{$row['narodnost']}</td>
                <td>
                    <a href='edit.php?id_budova={$row['id']}'>Edit</a> |
                    <a href='javascript:confirmBox({$row['id']})'>Delete</a>
                </td>
            </tr>";
        }
        ?>
    </tbody>
</table>

<p><a href="new.php">Pridaj novú budovu</a></p>

</body>
</html>