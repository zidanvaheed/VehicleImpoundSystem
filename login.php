<?php
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcomefile/welcome.php");
    exit;
}
 
require_once "config/config.php";
 
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
        $sql = "SELECT emp_id, username, password FROM employeelogin WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $password);
                    if(mysqli_stmt_fetch($stmt)){
                        if($_POST['password'] === $password){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["emp_id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            header("location: welcomefile/welcome.php");
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ 
            font: 20px sans-serif;
            color: white; 
            background-image: radial-gradient(circle, cyan, 27%, black);
        }
        .container{ 
            height: 100vh;
            padding-left: 150px;
        }
        .form-box{ 
            width: 70%;
            padding-left: 200px;
            padding-top: 200px;
        }
        #btn:hover{
            background-color: black;
        }
        a{
            color: greenyellow;
        }
        .pulseholder{
            height: 100px;
            padding-top: 9px;
        }
        .pulse {
            display: block;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #b5378f;
            cursor: pointer;
            box-shadow: 0 0 0 rgba(181,55,143, 0.4);
            animation: pulse 2s infinite;
        }
        .pulse:hover {
            animation: none;
        }

        @-webkit-keyframes pulse {
            0% {
                -webkit-box-shadow: 0 0 0 0 rgba(181,55,143, 0.4);
            }
            70% {
                -webkit-box-shadow: 0 0 0 10px rgba(181,55,143, 0);
            }
            100% {
                -webkit-box-shadow: 0 0 0 0 rgba(181,55,143, 0);
            }
        }
        @keyframes pulse {
            0% {
                -moz-box-shadow: 0 0 0 0 rgba(181,55,143, 0.4);
                box-shadow: 0 0 0 0 rgba(181,55,143, 0.4);
            }
            70% {
                -moz-box-shadow: 0 0 0 10px rgba(181,55,143, 0);
                box-shadow: 0 0 0 10px rgba(181,55,143, 0);
            }
            100% {
                -moz-box-shadow: 0 0 0 0 rgba(181,55,143, 0);
                box-shadow: 0 0 0 0 rgba(181,55,143, 0);
            }
        }
    </style>
</head>
<body>
    <div class="container" >
        <div class="form-box">
            <div class="mainbox">
                <h2>Login</h2>
                <p>Please fill in your credentials to login.</p>

                <?php 
                if(!empty($login_err)){
                    echo '<div class="alert alert-danger">' . $login_err . '</div>';
                }        
                ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                        <span class="invalid-feedback"><?php echo $username_err; ?></span>
                    </div>    
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" id="btn" value="Login">
                    </div>
                    <div class="pulseholder" style="overflow: hidden;">
                    <p style="float: left;">Admin priviledge only - <a href="admin/admin.php">Proceed&nbsp;</a></p><p class="pulse" style="float: left;"></p>
                    </div>
                </form>
            </div>
        </div>    
    </div>
</body>
</html>