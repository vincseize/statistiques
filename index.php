<?php
define("N_SPORTS","3");
?>
<style>
    body,html {
        margin:0;
        padding:0;
        font-family: Arial;
    }
    .div_bt_agrandir{
        margin:0;
        padding:0;
        display: none;
        position: absolute;
        top: 0;
        left: 0;
    }
    .resultats_database{
        display: block;
        background-color: yellow;
    }
    .div_show_hide{
        background-color: yellow;
        width:100%;
        height:25px;
        margin:0;
        padding:0;
    }
    .show_hide{
        background-color: yellow;
        font-size: 16px; 
        position: absolute;
        top: 0;
        right: 0;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        color: #588c7e;
        font-family: monospace;
        font-size: 20px;
        text-align: left;
    }
    th {
        background-color: #588c7e;
        color: white;
        text-align: center;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2
    }
    td {
        text-align: center;
    }

</style>

<script type="text/javascript">
    function toggleContent() {
        // Get the DOM reference
        var contentIdDb = document.getElementById("resultats_database");
        var contentIdAgrandir = document.getElementById("div_bt_agrandir");
        // Toggle 
        contentIdDb.style.display == "block" ? contentIdDb.style.display = "none" : contentIdDb.style.display = "block"; 
        contentIdAgrandir.style.display == "block" ? contentIdAgrandir.style.display = "none" : contentIdAgrandir.style.display = "block"; 
    }
    function increaseFontSize(objId, plusOUmoins) {
        obj = document.getElementById(objId);
        currentSize = parseFloat(obj.style.fontSize);
        if(plusOUmoins=='+'){
            obj.style.fontSize = (currentSize + .1) + "em";
        }
        if(plusOUmoins=='-'){
            obj.style.fontSize = (currentSize - .1) + "em";
        }
    }
</script>

<div id="div_bt_agrandir" class="div_bt_agrandir">
    <input type="button" value="AGRANDIR texte" onclick="increaseFontSize('resultats_database', '+');" />
    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" value="DIMINUER texte" onclick="increaseFontSize('resultats_database', '-');" />
</div>
<div class="div_show_hide">&nbsp;
    <button id="show_hide" class="show_hide" onclick="toggleContent()">SHOW | HIDE debug</button>
</div>
<div id="resultats_database" class="resultats_database" style="display:none;font-size: 0.6em;">
    <?php
        include('inc/insert_donnees.php');
    ?>
    <br><br>
</div>
<br>


<input type="button" value="NOUVEAUX ELEVES ALEATOIRES (avec ecoles/sports)" onclick="window.location.href='index.php'" />
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="RESET" onclick="window.location.href='index.php?VAR_VIDER'" />
 
<br><br>
<i>N_SPORTS variable, definie en haut du fichier index.php</i>
<br><br>

<div class="resultats">


    <table>
    <tr>
    <th>Id</th>
    <th>Nom</th>
    <th>Nombre Eleves</th>
    <th>Nombre Eleves au moins <?php echo N_SPORTS;?> sport(s)</th>
    <th>Nombre Activités</th>
    <th>Activités</th>
    </tr>
    <?php

        include('inc/inc_connect.php');

        function compte_eleves($conn,$id_ecole){
            $sql = "SELECT id FROM eleves WHERE id_ecole = '$id_ecole'";
            $results = $conn->query($sql);
            $nombre_eleves = $results->num_rows;
            return $nombre_eleves;
        }

        function compte_eleves_au_moins_N_sport($conn,$id_ecole){
            $nombre_eleves = 0;
            // corresponds à l eleve qui a le plus d activité
            $nombre_activites = 0;
            $sql = "SELECT ids_sport FROM eleves WHERE id_ecole = '$id_ecole'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $ids_sports = unserialize($row["ids_sport"]);
                    if (count($ids_sports)>3){
                        $nombre_eleves++;
                    }
                    if (count($ids_sports)>$nombre_activites){
                        $nombre_activites = count($ids_sports);
                    }
                }
            }

            return array($nombre_eleves,$nombre_activites);
        }

        $sql = "SELECT id, ecole FROM ecoles";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {

            while($row = $result->fetch_assoc()) {
                $id_ecole = $row["id"];
                $nom_ecole = $row["ecole"];
                $nombre_eleves = compte_eleves($conn,$id_ecole);
                $nombre_eleves_au_moins_un_sport = compte_eleves_au_moins_N_sport($conn,$id_ecole)[0];
                $nombre_activites = compte_eleves_au_moins_N_sport($conn,$id_ecole)[1];
                $synteses_activites = "en cours";

                echo "<tr><td>" . $id_ecole. 
                "</td><td>" . $nom_ecole .
                "</td><td>" . $nombre_eleves . 
                "</td><td>" . $nombre_eleves_au_moins_un_sport . 
                "</td><td>" . $nombre_activites . 
                "</td><td>". $synteses_activites. "</td></tr>";
            }
        echo "</table>";
        } else { echo "0 results"; }
        $conn->close();
    ?>
    </table>



</div>
