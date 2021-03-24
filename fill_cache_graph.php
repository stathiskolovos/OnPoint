<?php 
    include "config.php";

    $content_types = $_POST['content_types'];
    $isp = $_POST['isp'];
    $result = [];

    $sql1 = "SELECT ROUND((SELECT COUNT(*) FROM entries WHERE FIND_IN_SET('public',cache_control) 
    AND content_type IN $content_types AND isp IN $isp)*100/(SELECT count(*) FROM entries WHERE content_type IN $content_types AND isp IN $isp), 2) AS public_percentage";
    $sql2 = "SELECT ROUND((SELECT COUNT(*) FROM entries WHERE FIND_IN_SET('private',cache_control) 
    AND content_type IN $content_types AND isp IN $isp)*100/(SELECT count(*) FROM entries WHERE content_type IN $content_types AND isp IN $isp), 2) AS private_percentage";
    $sql3 = "SELECT ROUND((SELECT COUNT(*) FROM entries WHERE FIND_IN_SET('no-cache',cache_control) 
    AND content_type IN $content_types AND isp IN $isp)*100/(SELECT count(*) FROM entries WHERE content_type IN $content_types AND isp IN $isp), 2) AS no_cache_percentage";
    $sql4 = "SELECT ROUND((SELECT COUNT(*) FROM entries WHERE FIND_IN_SET('no-store',cache_control) 
    AND content_type IN $content_types AND isp IN $isp)*100/(SELECT count(*) FROM entries WHERE content_type IN $content_types AND isp IN $isp), 2) AS no_store_percentage";


    $query1 = mysqli_query($link, $sql1);
    $query2 = mysqli_query($link, $sql2);
    $query3 = mysqli_query($link, $sql3);
    $query4 = mysqli_query($link, $sql4);
   
    while ($row1 = $query1->fetch_array(MYSQLI_ASSOC)){
        while ($row2 = $query2->fetch_array(MYSQLI_ASSOC)){
            while ($row3 = $query3->fetch_array(MYSQLI_ASSOC)){
                while ($row4 = $query4->fetch_array(MYSQLI_ASSOC)){
                    $result [] = $row1['public_percentage']." ".$row2['private_percentage']." ".$row3['no_cache_percentage']." ".$row4['no_store_percentage']." ";
                }
            }
        }
    }

    mysqli_close($link);
    header('Content-Type: application/json');
    echo json_encode($result);
?>   
