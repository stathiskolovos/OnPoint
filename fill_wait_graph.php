<?php 
    session_start();

    include "config.php";

    $content_types = $_POST['content_types'];
    $methods = $_POST['methods'];
    $days = $_POST['days'];
    $isp = $_POST['isp'];

    
    $sql = "SELECT HOUR(StartedDateTime) as hour, 
    Round(AVG(wait),2) as avg FROM entries
    WHERE DAYNAME(StartedDateTime) in $days
    AND method in $methods
    AND content_type in $content_types
    AND isp in $isp 
    GROUP BY HOUR(StartedDateTime)";

    $query = mysqli_query($link,$sql);
    $result = [];
    while ($row = $query->fetch_array(MYSQLI_ASSOC)) { 
        $result [] = [$row['hour'],$row['avg']];
    }
    mysqli_close($link);
    header('Content-Type: application/json');
    echo json_encode($result);
?>
