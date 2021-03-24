<?php
    session_start();
 
    include "config.php";

    $user_id = $_SESSION['id'];
    $sql = "SELECT last_upload FROM users WHERE id = $user_id";
    $result = mysqli_query($link, $sql);
    while ($row = mysqli_fetch_array($result)) {
        echo $row['last_upload'];
    }

    mysqli_close($link);
?>