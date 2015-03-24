<?php
    $qBaseId = $_POST["qBaseId"];
    
    mysql_connect("localhost", "root", "") || die("couldn't connect to the database");
    mysql_select_db("qwerty") || die("couldn't find database'");
    
    $pointList = array();
    $query = mysql_query("SELECT points FROM qpoints WHERE qbase_id = '$qBaseId'");
    
    if(!$query){
        die();
    } else {
        while ($pointsRow=  mysql_fetch_array($query)){
            array_push($pointList, $pointsRow["points"]);
        }

        echo json_encode($pointList);
    }
?>
