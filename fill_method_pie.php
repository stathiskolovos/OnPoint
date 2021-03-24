<?php 
    include "config.php";

    $methods = ['GET', 'POST', 'PUT', 'HEAD', 'DELETE', 'PATCH', 'OPTIONS'];
    $result = [];

    foreach($methods as $a){
        $sql = "SELECT COUNT(*) as total FROM entries WHERE method = '$a'";
        $query = mysqli_query($link,$sql);
        while ($row = $query->fetch_array(MYSQLI_ASSOC)) { 
            $result [] = $row['total'];
        }
        
    }

    mysqli_close($link);
    header('Content-Type: application/json');
    echo json_encode($result);
?>