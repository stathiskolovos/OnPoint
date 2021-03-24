<?php
    include 'config.php';

    $result = [];

    $sql = "SELECT user_lat, user_long, latitude, longitude, COUNT(id)*100/(SELECT count(*) from entries) 
    AS thick FROM entries GROUP BY user_lat, user_long, latitude, longitude";
    
    $query = mysqli_query($link, $sql);
    while ($row = $query->fetch_array(MYSQLI_ASSOC)){
        if($row['latitude'] != 0 && $row['longitude'] != 0){
            $result [] = [$row['user_lat'] , $row['user_long'] ,$row['latitude'] , $row['longitude'], $row['thick']];
        }
    }

    mysqli_close($link);
    header('Content-Type: application/json');
    echo json_encode($result);

?>