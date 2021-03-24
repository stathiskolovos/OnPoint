<?php
    session_start();
    include 'config.php';

    $last_upload = $_POST['last_upload'];
    $user_id = $_SESSION['id'];

    $sql = "UPDATE users SET last_upload = $last_upload WHERE id = $user_id";

    if (mysqli_query($link, $sql)){
        echo json_encode(array("statusCode"=>200));
    }
    else{
        echo json_encode(array("statusCode"=>201));
    }
    
    mysqli_close($link);
?>
