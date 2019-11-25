<?php
define("N_SPORTS","4");
?>
<html>
<head>
    <title>statistiques Valérie</title>
    <link rel="stylesheet" type="text/css"href="css/styles.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/scripts.js"></script>
</head>

<body>

    <div id="loader" >&nbsp;<div class="loader"></div> </div>


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


    &nbsp;&nbsp;&nbsp;&nbsp;

    <input type="button" value="NOUVEAUX ELEVES ALEATOIRES (avec ecoles/sports)" onclick="RELOAD();" />
    &nbsp;&nbsp;&nbsp;&nbsp;

    <input type="button" value="RESET" onclick="VIDER_TABLES();" />

    <br><br>
    &nbsp;&nbsp;&nbsp;&nbsp;<b>Faire pas mal de RESET pour voir que cela marche (surtout pour synthèse), de la construction aléatoire!</b>
    <br><br>
    &nbsp;&nbsp;&nbsp;&nbsp;<i>N_SPORTS variable, definie en haut du fichier index.php</i>
    <br>
    &nbsp;&nbsp;&nbsp;&nbsp;<i>aleatoire pas de sport pour un élève, insert_donnees.php, line 124</i>
    <br><br>

    <div class="resultats">


        <table>
        <tr>
        <!-- <th>Id</th> -->
        <th>Nom</th>
        <th>Nb Eleves</th>
        <th>Nb Eleves <br>sports >= <?php echo N_SPORTS;?></th>
        <th>Nb Activités Effectuées</th>
        <th>Synthèse Activités #=n elèves</th>
        </tr>
        <?php

            include('inc/inc_connect.php');

            function syntheses_activites($conn,$id_ecole){
                $syntheses_activites = array();
                $sql = "SELECT ids_sport FROM eleves WHERE id_ecole = '$id_ecole'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $ids_sports = unserialize($row["ids_sport"]);
                        // on enleve les doublons
                        $ids_sports = array_unique($ids_sports);
                        foreach ($ids_sports as $id_sport){
                            array_push($syntheses_activites,$id_sport);
                        }
                    }
                }
                // on enleve les doublons
                $syntheses_activites = array_unique($syntheses_activites);
                // echo "<br>------";
                // print_r($syntheses_activites);
                // echo "<br>";
                return $syntheses_activites;
            }

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
                        if (count($ids_sports)>N_SPORTS){
                            $nombre_eleves++;
                        }
                        if (count($ids_sports)>$nombre_activites){
                            $nombre_activites = count($ids_sports);
                        }
                    }
                }

                return array($nombre_eleves,$nombre_activites);
            }

            function find_idSport($conn,$id_ecole,$id_sport,$n_syntheses_activites){
                
                $sql = "SELECT sport FROM sports WHERE id = '$id_sport'";
                        $r2 = $conn->query($sql);
                        if ($r2->num_rows > 0) {
                            while($row = $r2->fetch_assoc()) {
                                $sport = $row["sport"];
                                $n_eleves = 0;

                                $sql3 = "SELECT id,ids_sport,id_ecole FROM eleves WHERE id_ecole = '$id_ecole'";
                                $r3 = $conn->query($sql3);
                                if ($r3->num_rows > 0) {
                                    while($row = $r3->fetch_assoc()) {
                                        $ids_sports = unserialize($row["ids_sport"]);
                                        if (in_array($id_sport, $ids_sports)) {
                                            $n_eleves++;
                                        }
                                    }
                                }

                                $n_syntheses_activites .= ' #'.strval($n_eleves).$sport.',';
                            }
                        }
                return $n_syntheses_activites;
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
                    $syntheses_activites = syntheses_activites($conn,$id_ecole);
                    $ns = intval(count($syntheses_activites))-1;
                    $n_syntheses_activites = $ns." possible(s) | ";

                    foreach ($syntheses_activites as $id_sport){
                        // $nom_activites = '';
                        $n_syntheses_activites = find_idSport($conn,$id_ecole,$id_sport,$n_syntheses_activites);
                    }


                    // tri
                    $array_synthese = array();
                    $tmp = explode(" | ",$n_syntheses_activites)[1];
                    $tmp = explode(",",$tmp);
                    // print_r($tmp);
                    // print_r("<br>");
                    // $array_synthese_couple = array();
                    foreach($tmp as $value){
                        
                        // try{
                            // print_r($array_synthese);
                            
                            if(!empty($value)){
                                array_push($array_synthese,$value);
                            }

                        // }
                        // catch(Exception $e){
                        // }
                    }


                    
                    // $array_synthese = array_unique($array_synthese);   

                    // print_r($array_synthese);
                    sort($array_synthese);
                    // echo "<br><br>";
                    $array_synthese = array_reverse($array_synthese);
                    print_r($array_synthese);
                    echo "<br><br>";
                    $n_syntheses_activites = $ns." possible(s) | ";
                    foreach($array_synthese as $value){
                        $n_syntheses_activites .= $value.',';
                    }


                    // echo "<tr><td>" . $id_ecole.
                    echo "<tr><td>" . $nom_ecole. 
                    // "</td><td>" . $nom_ecole .
                    "</td><td>" . $nombre_eleves . 
                    "</td><td>" . $nombre_eleves_au_moins_un_sport . 
                    "</td><td>" . $nombre_activites . 
                    "</td><td class='td_synthese'>". $n_syntheses_activites. "</td></tr>";
                }
            echo "</table>";
            } else { echo "0 results"; }
            $conn->close();
        ?>
        </table>



    </div>


    <br><br>
    &nbsp;&nbsp;&nbsp;&nbsp;<b>Question complémentaire - Réponse proposée:</b>
    <br>
    &nbsp;&nbsp;&nbsp;&nbsp;- filtres plus poussés avec Interface de choix, combo list, checkbox ..., par écoles, élèves, sports, etc
    <br><br>



    <script type="text/javascript">
        $(document).ready(function() {
            // alert("I am an alert box!");
            $(loader).hide();
        });
    </script>

</body>
</html>
