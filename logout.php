<?php
    // έναρξη 'νέου' session
    session_start();
    
    // άδεισμα των πληροφοριών του session(username,id,loggedin)
    $_SESSION = array();
    
    session_destroy();
    
    //-> loginPage
    header("location: loginPage.php");
exit;
?>