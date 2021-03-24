<?php  
    include "config.php";

    session_start();

    $user_id = $_SESSION['id'];
    $result = [];

    $sql = "SELECT latitude, longitude FROM entries WHERE user_id = $user_id";
    $query = mysqli_query($link,$sql);
    while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
            $result [] = [$row['latitude'],$row['longitude']];
    }
    mysqli_close($link);
    header('Content-Type: application/json');
    echo json_encode($result);   
?>