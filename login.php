<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: home.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM Users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["name"] = $name;                            
                            
                            // Redirect user to home page
                            header("location: home.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
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
        <title>COVID - 19 Contact Tracing</title>
    </head>
    <body>
        <h1>COVID - 19 Contact Tracing</h1>
        <div class="error-message" id="error">
            <?php 
            if(!empty($login_err)){
                echo '<div class="login-error" style="text-align: center">' . $login_err . '</div>';
            }        
            ?>    
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="loginpage">
            <img style="left: 25%;" id="watermark" src="img/watermark.png">
            <div class="login"></div>
                <input type="text" placeholder="Username" name="username" id="username" style="margin-top: 10%" required class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>"><br><br>
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
                <input type="password" placeholder="Password" name="password" id="password" required class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"><br><br>
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
                <button type="submit" name="login" id="login" style="margin-right: 10px">Login</button>
                <button type="reset" name="cancel" id="cancel" value="Reset">Cancel</button><br><br>
                <button type="submit" name="register" value="Register" style="padding: 10px 275px 10px 275px;" id="register" onclick="location.href='registration.php'">Register</button>
            </div>
        </form>
        <script>  
        function validation()  
        {  
            var uname=document.loginpage.username.value;  
            var pword=document.loginpage.password.value;  
            if(uname.length=="" && pword.length=="" ) {  
                alert("Username and Password fields are empty");  
                return false;  
            }  
            else  
            {   
                if(uname.length=="") {  
                    alert("Username is empty");  
                    return false;  
                }   
                if (pword.length=="") {  
                    alert("Password field is empty");  
                    return false;  
                }  
            }                             
        }  
    </script>
    </body>
</html>
