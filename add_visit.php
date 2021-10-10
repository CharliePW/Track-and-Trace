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
$visit_id_err = $user_id_err = $date_err = $time_err = $duration_err = $x_err = $y_err = "";

$username = $_SESSION["username"];
$sql = "SELECT id FROM Users WHERE username = '$username'";
$result = mysqli_query($link, $sql);
$rs = mysqli_fetch_array($result);

$user_id = $rs['id'];
$_SESSION["user_id"] = $user_id;

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate date
    if(empty(trim($_POST["Date"]))){
        $date_err = "Please enter a date.";     
    } else{
        $date = trim($_POST["Date"]);
    }

    // Validate time
    if(empty(trim($_POST["Time"]))){
        $time_err = "Please enter a time.";     
    } else{
        $time = trim($_POST["Time"]);
    }

    // Validate duration
    if(empty(trim($_POST["Duration"]))){
        $duration_err = "Please enter a duration.";     
    } else{
        $duration = trim($_POST["Duration"]);
    }

    // POST x_value
    $x = trim($_POST["X"]);

    // POST y_value
    $y = trim($_POST["Y"]);
    
    // Check input errors before inserting in database
    if(empty($date_err) && empty($time_err) && empty($duration_err) && empty($x_err) && empty($y_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO Visits (User_id, Date, Time, Duration, X, Y) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){

            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $user_id, $date, $time, $duration, $x, $y);
            
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
        <title>Add a new Visit</title>

        <script type="text/javascript">

            function FindPosition(oElement) {
            
                if(typeof( oElement.offsetParent ) != "undefined") {
                    for(var posX = 0, posY = 0; oElement; oElement = oElement.offsetParent) {
                        posX += oElement.offsetLeft;
                        posY += oElement.offsetTop;
                    }
                    return [ posX, posY ];
                }
                else {
                    return [ oElement.x, oElement.y ];
                }
            }

            function GetCoordinates(e) {

                var PosX = 0;
                var PosY = 0;
                var ImgPos;
                ImgPos = FindPosition(myImg);
                if (!e) var e = window.event;
                if (e.pageX || e.pageY) {
                    PosX = e.pageX;
                    PosY = e.pageY;
                }
                else if (e.clientX || e.clientY) {
                    PosX = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
                    PosY = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
                }
                PosX = PosX - ImgPos[0];
                PosY = PosY - ImgPos[1];
                
                addToForm("X", PosX);
                addToForm("Y", PosY);
            }

            function addToForm(name, value) {
                var input = document.createElement("input");
                input.setAttribute("type", "hidden");
                input.setAttribute("name", name);
                input.setAttribute("value", value);
                document.getElementById("main").appendChild(input);
            }

            function place_marker() 
            {
                document.getElementById("exeter_map").onclick = function(e) 
                {
                with(document.getElementById("black_marker")) 
                {   
                    var x_value = e.pageX - 20;
                    var y_value = e.pageY - 42;
                    style.left = x_value + "px";
                    style.top = y_value + "px";
                    style.display = "block";
                }
                };
            }

        </script>
        
    </head>
    <body>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="add_visit">

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

            <div class="main" id="main">
 
                <div class="Add a new Visit" style="text-align: center; font: 24pt Arial;">
                        <p>
                            Add a new Visit
                        </p>
                </div>
                <hr />

                <img id="exeter_map" src="img/exeter.jpg" onclick="place_marker()">
                <img id="black_marker" src="img/marker_black.png" style="height: 40px; display: none; position: absolute">
                <script>         
                    var myImg = document.getElementById("exeter_map");
                    myImg.onmousedown = GetCoordinates;
                </script>  
            
                <input type="date" id="Date" name="Date" placeholder="Date" required style="top: 27%; padding: 10px 30px 10px 30px; margin-left: 9.5%"><br><br>
                <input type="time" id="Time" name="Time" placeholder="Time" style="top: 37%; padding: 10px 92px 10px 92px; margin-left: 9.5%"><br><br>
                <input type="text" id="Duration" name="Duration" placeholder="Duration (in mins)" style="top: 47%; padding: 10px 11px 10px 11px; margin-left: 9.5%">
                <button type="submit" name="Add" value="Add" id="Add" style="top: 72%">Add</button><br><br>
                <button type="reset" id="Cancel" style="top: 82%">Cancel</button>
            </div>
        </form>
    </body>
</html>