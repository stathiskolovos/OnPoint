<?php
    
    //Διαπιστευτήρια για τη σύνδεση στη ΒΔ 
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'OnPoint');
 
    //Συνδεση
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    //Αν δεν είναι δυνατή η σύνδεση error
    if($link === false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
?>