<?php
    session_start();
 
    include "config.php";

    $user_id = $_SESSION['id'];
    $sql = "SELECT COUNT(id) as total FROM entries WHERE user_id = $user_id";
    $result = mysqli_query($link, $sql);
    while ($row = mysqli_fetch_array($result)) {
        echo $row['total'];
    }

    mysqli_close($link);
?>