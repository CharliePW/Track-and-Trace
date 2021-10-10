<?php 

// start session
session_start();

// Create connection
require_once "config.php";

$visit_id = $_POST["visit_id"];

if($visit_id > 0){
    // check records exist
    $sql = "SELECT * FROM Visits WHERE Visit_id = '$visit_id'";
    $result = mysqli_query($link, $sql);
    $num_rows = mysqli_num_rows($result);

    if($num_rows > 0) {
        // Delete record
        $sql = "DELETE FROM Visits WHERE Visit_id = '$visit_id'";
        mysqli_query($link,$sql);
        echo 1;
        exit;
    } else {
        echo 0;
        exit;
    }
}
echo 0;
exit;