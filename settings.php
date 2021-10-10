<?php

//Initializing the session
session_start();

if(!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $window = $_POST["window"];
    $distance = $_POST['distance'];

    setcookie('window', $window, time() + (86400 * 30), '/');
    setcookie('distance', $distance, time() + (86400 * 30), '/');
}


?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="styles.css" />
        <title>Settings</title>
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
                    Settings
                </p>
            </div>
            <hr />
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="report_page">
                <div class="reportText" style="width: 65%; margin-left: 17%;">
                    <p>
                        Here you may change the alert distance and the time span for which the contact tracing will be performed.
                    </p>
                </div>
                window
                <select id="window" name="window" required style="margin-top: 5%; margin-left: 3%; padding: 10px 210px 10px 210px; background-color: transparent; font: 20pt 'Times New Roman'">
                    <option value="select"> </option>
                    <option value="1">1 Week</option>
                    <option value="2">2 Weeks</option>
                    <option value="3">3 Weeks</option>

                </select>
                <!--<input type="text" name="window" required style="margin-top: 5%; margin-left: 3%; padding: 10px 130px 10px 130px">-->
                <br><br>
                distance
                <input type="text" name="distance" required style="margin-left: 3%; padding: 10px 130px 10px 130px"><br><br>
                <button type="submit" style="padding: 10px 50px 10px 50px; margin-right: 35%"> Report </button>
                <button type="reset" style="padding: 10px 50px 10px 50px;"> Cancel </button>
            </form>
        </div>
    </body>
</html>