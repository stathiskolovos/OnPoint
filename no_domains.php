<?php
    include "config.php";

    $sql = "SELECT COUNT(DISTINCT url) as total FROM entries";
    $result = mysqli_query($link, $sql);
    while ($row = mysqli_fetch_array($result)) {
        echo $row['total'];
    }
    mysqli_close($link);
?>