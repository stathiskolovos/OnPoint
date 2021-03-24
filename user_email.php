<?php
    session_start();
 
    include "config.php";

    $user_id = $_SESSION['id'];
    $sql = "SELECT email FROM users WHERE id = $user_id";
    $result = mysqli_query($link, $sql);
    while ($row = mysqli_fetch_array($result)) {
        echo $row['email'];
    }

    mysqli_close($link);
?>