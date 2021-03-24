<?php 
    include "config.php";
    $result = [];

    $sql = "SELECT DISTINCT content_type from entries ORDER BY content_type ASC";
    $query = mysqli_query($link,$sql);
    while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
        if($row['content_type'] != ""){
            $result [] = $row['content_type'];
        } 
        
    }
    mysqli_close($link);
    header('Content-Type: application/json');
    echo json_encode($result);                             
?>   
