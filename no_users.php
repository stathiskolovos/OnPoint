<?php 
    include "config.php";

    $sql = "SELECT COUNT(id) as total from users WHERE id not in(select id from users WHERE username='admin')";
    $result = mysqli_query($link, $sql);
    while ($row = mysqli_fetch_array($result)) {
        echo $row['total'];
    }

    mysqli_close($link);
?>