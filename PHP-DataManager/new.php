<?php

require('connect.php');

if (!$spojenie):
    echo "Spojenie s databázou neprebehlo!";
else:

    
    if (isset($_GET['uloz_budovu'])) {
        
        $nazov = mysqli_real_escape_string($spojenie, $_GET['nazov']);
        $lokalita = mysqli_real_escape_string($spojenie, $_GET['lokalita']);
        $vyska = mysqli_real_escape_string($spojenie, $_GET['vyska']);
        $pocet_poschodi = mysqli_real_escape_string($spojenie, $_GET['pocet_poschodi']);
        $rok_dokoncenia = mysqli_real_escape_string($spojenie, $_GET['rok_dokoncenia']);
        $meno_architekt = mysqli_real_escape_string($spojenie, $_GET['meno_architekt']);
        $narodnost_architekt = mysqli_real_escape_string($spojenie, $_GET['narodnost_architekt']);

        
        $sql_budova = "INSERT INTO x_richtarik_Budovy (nazov, lokalita, vyska, pocet_poschodi, rok_dokoncenia) 
                       VALUES ('$nazov', '$lokalita', '$vyska', '$pocet_poschodi', '$rok_dokoncenia')";

        if (mysqli_query($spojenie, $sql_budova)) {
            
            $id_budova = mysqli_insert_id($spojenie);

            
            $sql_architekt = "INSERT INTO x_richtarik_Architekti (meno, narodnost, budova_id) 
                              VALUES ('$meno_architekt', '$narodnost_architekt', $id_budova)";

            if (mysqli_query($spojenie, $sql_architekt)) {
                echo "Budova a architekt boli úspešne pridaní!";
                header("Location: index.php"); 
                exit;
            } else {
                echo "Chyba pri pridávaní architekta: " . mysqli_error($spojenie);
            }
        } else {
            echo "Chyba pri pridávaní budovy: " . mysqli_error($spojenie);
        }
    }
?>


<h2>Pridať novú budovu</h2>
<form method="get" action="new.php">
    <table>
        <tr>
            <td>Názov budovy:</td>
            <td><input type="text" name="nazov" required></td>
        </tr>
        <tr>
            <td>Lokalita:</td>
            <td><input type="text" name="lokalita" required></td>
        </tr>
        <tr>
            <td>Výška budovy (v metroch):</td>
            <td><input type="number" name="vyska" required min="1"></td>
        </tr>
        <tr>
            <td>Počet poschodí:</td>
            <td><input type="number" name="pocet_poschodi" required min="1"></td>
        </tr>
        <tr>
            <td>Rok dokončenia:</td>
            <td><input type="number" name="rok_dokoncenia" required min="1000" max="9999"></td>
        </tr>
        <tr>
            <td>Meno architekta:</td>
            <td><input type="text" name="meno_architekt" required></td>
        </tr>
        <tr>
            <td>Národnosť architekta:</td>
            <td><input type="text" name="narodnost_architekt" required></td>
        </tr>
    </table>
    <input type="submit" name="uloz_budovu" value="Pridať budovu">
</form>

<?php
endif;
?>

