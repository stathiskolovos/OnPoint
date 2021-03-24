<?php
    include "config.php";

    $status = ["status <='199'", "status >='200' AND status<='299'", "status >='300' AND status<='399'", "status >='400' AND status<='499'", "status >='500' AND status<='599'"];
    $result = [];

    foreach($status as $a)
    {
        $sql= "SELECT COUNT(*) as total from entries where $a";
        $query = mysqli_query($link,$sql);
        while ($row = $query->fetch_array(MYSQLI_ASSOC)) { 
            $result [] = $row['total'];
        }
    }

    mysqli_close($link);
    header('Content-Type: application/json');
    echo json_encode($result);
?>