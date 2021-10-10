<?php

//Initializing the session
session_start();

// Include config file
require_once "config.php";

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$username = $_SESSION["username"];
$sql = "SELECT name, id FROM Users WHERE username = '$username'";
$result = mysqli_query($link, $sql);
$rs = mysqli_fetch_array($result);

$name = $rs['name'];
$_SESSION["name"] = $name;

$user_id = $rs['id'];
$_SESSION["user_id"] = $user_id;

$window = $_COOKIE["window"];
$distance = $_COOKIE["distance"];

/*$sql = "SELECT Users.id, Infections.Date FROM
Users INNER JOIN Infections ON Users.id = Infections.User_id
INNER JOIN Visits ON Users.id = Visits.User_id
WHERE Visits.Date BETWEEN DATE_SUB(CURDATE(), INTERVAL $window DAY) AND CURDATE()
AND EXISTS
(
 SELECT * FROM Visits V
 WHERE V.User_id = $user_id
 AND SQRT (POWER(V.X - Visits.X, 2) + POWER(V.Y - Visits.Y, 2)) $distance
 AND (Visits.Date BETWEEN V.Date AND DATE_ADD(V.Date, INTERVAL V.Duration MINUTE)
 OR (V.Date BETWEEN Visits.Date AND DATE_ADD(Visits.Date, INTERVAL Visits.Duration MINUTE)))
);";

$result = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($result) ){ */



?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="styles.css" />
        <title>Home Page</title>
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
            <div class="Status" style="text-align: center; font: 24pt Arial;">
                <p>
                    Status
                </p>
            </div>
            <hr />
            <div class="exeter" style="position: absolute;">
                <img src="img/exeter.jpg">
            </div>
            
            <div class="text" style="width: 15%; margin-left: 10%;">
                
                    Hi <?php echo htmlspecialchars($_SESSION["name"]); ?>, you might have had a connection to an infected person at the location shown in red.
                
                <br><br><br><br>
                <p>
                    Click on the marker to see details about the infection.
                </p>
            </div>

        </div>
    </body>
</html>