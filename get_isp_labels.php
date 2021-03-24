<?php 
    include "config.php";
    $result = [];

    $sql = "SELECT DISTINCT isp FROM entries ORDER BY isp ASC";
    $query = mysqli_query($link,$sql);
    while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
        if($row['isp'] != ""){//μερικά κενά από ad block
            $result [] = $row['isp'];
        } 
        
    }
    mysqli_close($link);
    header('Content-Type: application/json');
    echo json_encode($result);                             
?>   
