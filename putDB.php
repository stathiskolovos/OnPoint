<?php
    session_start();
    include 'config.php';

    $user_id = $_SESSION['id'];
    $entries = $_POST['entries'];

    $startedDateTime = $entries[0];
    $wait = $entries[1];
    $serverIPAddress = $entries[2];
    $method = $entries[3];
    $url = $entries[4];
    $status = $entries[5];
    $statusText = $entries[6];
    $isp = $entries[7];
    $latitude = $entries[8];
    $longitude = $entries[9];
    $user_ip = $entries[10];
    $content_type = $entries[11];
    $cache_control = $entries[12];
    $pragma = $entries[13];
    $expires = $entries[14];
    $age = $entries[15];
    $last_modified = $entries[16];
    $host = $entries[17];
    $req_content_type = $entries[18];
    $req_cache_control = $entries[19];
    $req_pragma = $entries[20];
    $req_expires = $entries[21];
    $req_age = $entries[22];
    $req_last_modified = $entries[23];
    $req_host = $entries[24];
    $user_lat = $entries[25];
    $user_long = $entries[26];
    
    $sql = "INSERT INTO entries 
    (id, user_id, startedDateTime, wait, serverIPAddress, method, url, status, statusText, isp, latitude, longitude, user_ip, content_type, cache_control,pragma,expires,age,last_modified,host,req_content_type,req_cache_control,req_pragma,req_expires,req_age,req_last_modified,req_host,user_lat,user_long) 
    VALUES 
    (NULL, '$user_id', '$startedDateTime', '$wait', '$serverIPAddress', '$method', '$url', '$status', '$statusText' , '$isp', '$latitude', '$longitude', '$user_ip', '$content_type', '$cache_control', '$pragma', '$expires', '$age', '$last_modified', '$host', '$req_content_type', '$req_cache_control', '$req_pragma', '$req_expires', '$req_age', '$req_last_modified', '$req_host','$user_lat',$user_long)";

    if (mysqli_query($link, $sql)){
        echo json_encode(array("statusCode"=>200));
    }
    else{
        echo json_encode(array("statusCode"=>201));
    }
    
    mysqli_close($link);
?>
