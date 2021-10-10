<?php

// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $surname = $username = $password = "";
$name_err = $surname_err = $username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    setcookie('window', 1, time() + (86400 * 30), '/');
    setcookie('distance', 20, time() + (86400 * 30), '/');

    // Validate name
    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter a name.";     
    } else{
        $name = trim($_POST["name"]);
    }

    // Validate surname
    $surname = trim($_POST["surname"]);

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM Users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){

                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 8){
        $password_err = "Password must have at least 8 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($name_err) && empty($surname_err)){
    
        // Prepare an insert statement
        $sql = "INSERT INTO Users (name, surname, username, password) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_name, $param_surname, $param_username, $param_password);
            
            // Set parameters
            $param_name = $name;
            $param_surname = $surname;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
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
        <div class="error-message" style="text-align: center;">
            <?php 
            if(empty($username_err) && !empty($password_err)) {
                echo $password_err;
            } 
            if(empty($password) && !empty($username_err)) {
               echo $username_err;
            }
            ?>
        </div>
    
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="registration_page" name="registration_page" onsubmit = "return validation()">
            <img style="left: 25%;" id="watermark" src="img/watermark.png">
            <div class="login"></div>
                <input type="text" placeholder="Name" name="name" style="margin-top: 10%"><br><br>
                <input type="text" placeholder="Surname" name="surname"><br><br>
                <input type="text" placeholder="Username" name="username" ><br><br>
                <input type="password" placeholder="Password" name="password" ><br><br>
                <br>
                <button type="submit" name="register" value="Register" style="padding: 10px 275px 10px 275px;" >Register</button>
            </div>
        </form>
        <script>  
        function validation()  
        {  
            var name=document.registration_page.name.value;  
            var uname=document.registration_page.username.value;  
            var pword=document.registration_page.password.value;  
            if(name.length=="" && uname.length=="" && pword.length=="" ) {  
                alert("Name, Username and Password fields are empty");  
                return false;  
            }  
            else  
            {   
                if(name.length=="") {  
                    alert("Name is empty");  
                    return false;  
                }
                else {
                    if(username.length=="") {  
                        alert("Username is empty");  
                        return false;  
                    }
                }   
                else {
                    if (pword.length=="") {  
                        alert("Password field is empty");  
                        return false;  
                    } 
                }  
            }
        }  
    </script>
    </body>
</html>