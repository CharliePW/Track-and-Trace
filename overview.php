<?php

// start session
session_start();

// Create connection
require_once "config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

?> 

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="styles.css" />
        <title>Overview</title>

    </head>
    <body>
        <img style="left: 35%;" id="watermark" src="img/watermark.png">
        <h1>COVID - 19 Contact Tracing</h1>
        <div class="sidebar" style="width:17%">
            <a href="home.php" style="margin-top: 50%">Home</a>
            <a href="overview.php">Overview</a>
            <a href="add_visit.php">Add Visit</a>
            <a href="report.php">Report</a>
            <a href="settings.php">Settings</a><br><br><br><br><br><br><br><br><br><br><br><br><br>
            <br><br><br><a href="logout.php">Logout</a>
        </div>
                
        <div class="main">
            <table style="width: 1000px; max-width: available;">
                <tr style="font-family: 'Arial'; font-size: 20pt;">
                    <th>Date</th>
                    <th>Time</th>
                    <th>Duration</th>
                    <th>X</th>
                    <th>Y</th>
                </tr>
                
                <?php
                $user_id = $_SESSION["user_id"];
                $sql = "SELECT Visit_id, Date, Time, Duration, X, Y FROM Visits WHERE User_id = '$user_id'";
                $result = mysqli_query($link,$sql);
                
                while($row = mysqli_fetch_array($result) ){
                    $visit_id = $row["Visit_id"];
                    $_SESSION["visit_id"] = $visit_id;
                    $date = $row['Date'];
                    $time = $row['Time'];
                    $duration = $row['Duration'];
                    $x = $row['X'];
                    $y = $row['Y'];
                ?>
                    <tr>
                        <td align="center"><?= $date; ?></td> 
                        <td align="center"><?= $time; ?></td>
                        <td align="center"><?= $duration; ?></td>
                        <td align="center"><?= $x; ?></td>
                        <td align="center"><?= $y; ?></td>
                        <td align="center">
                            <img class='delete' src="img/cross.png" id="del_<?= $visit_id;?>" style="height: 40px" >
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>
        <script>
            $(document).ready(function(){

                // Delete 
                $('.delete').click(function(){

                    var el = this;
                    var visit_id = this.id;
                    var split_id = visit_id.split("_");

                    // Delete id
                    //var deleteid = $(this).data('visit_id');
                    var deleteid = splitid[1];

                    // AJAX Request
                    $.ajax({
                        url: 'remove_visit.php',
                        type: 'POST',
                        data: { id:deleteid },
                        success: function(response){
                            // Remove row from HTML Table
                            $(this).remove();
                        }    
                    });
                });
            });
        </script>
    </body>
</html>
