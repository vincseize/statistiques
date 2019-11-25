    <?php
    include('inc/inc_connect.php');
    include('inc/prenoms.php');
    include('inc/noms.php');
    include('inc/sports.php');
    include('inc/ecoles.php');

    if(isset($_GET['VAR_VIDER'])) {
        define('VAR_VIDER', 'TRUE');
        echo "----------------- ON VIDE PUIS REMPLIE LA BASE <font color=blue>[$dbname]</font>";
    } else {
        define('VAR_VIDER', 'FALSE');
        echo "----------------- ON REMPLIE LA BASE <font color=blue>[$dbname]</font>";
    }

    
    function vider_table($conn, $table){
        $sql  = "TRUNCATE TABLE ".$table; 
        $results = $conn->query($sql);
        if($conn->query($sql) === TRUE){
          echo "<br><br>--> TABLE ".$table." a été vidée !";
        }
        else{
          echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    function insert_donnees($conn, $table, $column, $array){
        // global $conn; 
        if (VAR_VIDER=='TRUE'){
            vider_table($conn, $table);
        }
        
        echo "<br><br>----------------- Insert donnees <font color=blue>[".$table."]</font><br>";

        $sql = "SELECT id FROM sports";
        $r = $conn->query($sql);
        $nombre_sports = $r->num_rows;
        
        foreach ($array as $name){
            // Check if exist $value
            $sql = "SELECT $column FROM $table WHERE $column = '$name'";
            $results = $conn->query($sql);
            if($results->num_rows == '0')
            {         
                    $sql = "INSERT INTO $table ($column) VALUES ('$name')";
                    if($table == 'eleves'){
                        $id_ecole = intval(random_idEcoles($conn, 'ecoles'));
                        
                        $ids_sport = array();
                        $random_nombre_sports =  rand(0, $nombre_sports);

                        for ($x = 0; $x <= $random_nombre_sports; $x++) {

                            $id_sport = random_idSports($conn, 'sports');
                            array_push($ids_sport,$id_sport);
                          }

                        // $id_sport = random_idSports($conn, 'sports');
                        // $id_sport2 = '2';
                        // $id_sport3 = '999';
                        // array_push($ids_sport,$id_sport,$id_sport2,$id_sport3);

                        // on enleve les doublons
                         $ids_sport = array_unique($ids_sport);
                        // convert to text
                        $ids_sport = serialize($ids_sport);
                        $sql = "INSERT INTO $table ($column, id_ecole, ids_sport) VALUES ('$name', '$id_ecole', '$ids_sport')";
                    }


                    if ($conn->query($sql) === TRUE) {
                        echo $name.' <font color=blue>[ insert OK ]</font>, ';
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
            }
            else{
                echo $name.' <font color=red>[ already exist ]</font>, ';
            }
        }
        
    }

    function nom_prenom($array_noms, $array_prenoms){
        $array_nom_prenom = array();
        foreach ($array_noms as $value){
            $nom = $array_noms[array_rand($array_noms)];
            $prenom = $array_prenoms[array_rand($array_prenoms)];
            $nom_prenom = $nom." ".$prenom;
            array_push($array_nom_prenom,$nom_prenom);
        }
        return $array_nom_prenom;
    }

    function random_idEcoles($conn, $table){
        $ids = array();
        $sql = "SELECT id FROM $table";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($ids,$row["id"]);
            }
        }
        $id = $ids[array_rand($ids)];
        return $id;
    }

    function random_idSports($conn, $table){
        $ids = array();
        $sql = "SELECT id FROM $table";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($ids,$row["id"]);
            }
        }
        $id = $ids[array_rand($ids)];
        return $id;
    }

    // ----------- tests
    // $id_ecole = random_idEcoles($conn, 'ecoles');
    // echo $id_ecole;
    // $id_sport = random_idSports($conn, 'sports');
    // echo $id_sport;
    // exit;
    // -----------

    // on creait une liste aleatoire nom+prenom
    $array_nom_prenom = nom_prenom($array_noms, $array_prenoms);

    // On insert les donnees, TABLE, COL, ARRAY
    insert_donnees($conn, 'prenoms', 'prenom', $array_prenoms);
    insert_donnees($conn, 'noms', 'nom', $array_noms);
    insert_donnees($conn, 'eleves', 'nom_prenom', $array_nom_prenom);
    insert_donnees($conn, 'sports', 'sport', $array_sports);
    insert_donnees($conn, 'ecoles', 'ecole', $array_ecoles);

    // on ferme la connection SQL
    $conn->close();

?>
