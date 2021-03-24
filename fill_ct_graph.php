<?php 
    include "config.php";

    $sql = "SELECT content_type, round(AVG(age)/60,2) as average FROM entries GROUP BY content_type";
    $query = mysqli_query($link,$sql);
    while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
            $result [] = $row['average'];   
    }
    mysqli_close($link);
    header('Content-Type: application/json');
    echo json_encode($result);                             
?>   
