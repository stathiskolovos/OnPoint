<?php
    include "config.php";

    $sql = "SELECT COUNT(DISTINCT isp) as total FROM entries WHERE isp NOT IN('')";
    $result = mysqli_query($link, $sql);
    while ($row = mysqli_fetch_array($result)) {
        echo $row['total'];
    }

    mysqli_close($link);
?>