<?php

//Initializing the session
session_start();

// Include config file
require_once "config.php";
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Define variables and initialize with empty values
$date = $time = "";
$date_err = $time_err = "";

$username = $_SESSION["username"];
$sql = "SELECT id FROM Users WHERE username = '$username'";
$result = mysqli_query($link, $sql);
$rs = mysqli_fetch_array($result);

$user_id = $rs['id'];
$_SESSION["user_id"] = $user_id;

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate Date
    if(empty(trim($_POST["Date"]))){
        $date_err = "Please enter a date.";     
    } else{
        $date = trim($_POST["Date"]);
    }

    // Validate Time
    if(empty(trim($_POST["Time"]))){
        $time_err = "Please enter a time.";     
    } else{
        $time = trim($_POST["Time"]);
    }
    
    // Check input errors before inserting in database
    if(empty($date_err) && empty($time_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO Infections (User_id, Date, Time) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $user_id, $date, $time);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: home.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="styles.css" />
        <title>Report</title>
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
                    Report an Infection
                </p>
            </div>
            <hr />
            
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="report_page">
                <div class="reportText" style="width: 65%; margin-left: 17%;">
                    <p>
                        Please report the date and time when you were tested positive for COVID-19.
                    </p>
                </div>
                <input type="date" placeholder="Date" name="Date" id="Date" required style="margin-top: 5%"><br><br>
                <input type="time" placeholder="Time" name="Time" id="Time" style="padding: 10px 242px 10px 242px" required><br><br>
                <button type="submit" style="padding: 10px 50px 10px 50px; margin-right: 35%"> Report </button>
                <button type="reset" style="padding: 10px 50px 10px 50px;"> Cancel </button>
            </form>
        </div>
    </body>
</html>    