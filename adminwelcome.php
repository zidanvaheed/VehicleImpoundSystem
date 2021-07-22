<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: /admin.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ 
            font: 14px sans-serif; 
            text-align: center;
            background: linear-gradient(to right, #4776e6, #8e54e9);
        }
        .multibutton{
            padding-left: 375px;
            align-content: center;
        }
    </style>
</head>
<body>
    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to your account.</h1>
    <div class="multibutton" style="overflow: hidden;">
        <p style="float: left;">
            <a href="index.php" class="btn btn-success ml-3">Proceed to Employee Database</a>
        </p>
        <p style="float: left;">
            &nbsp;&nbsp;&nbsp;&nbsp;<a href="../crud/index2.php" class="btn btn-success ml-3">Proceed to Main Databse</a>
        </p>
        <p style="float: left;">
            &nbsp;&nbsp;&nbsp;&nbsp;<a href="../crud/order.php" class="btn btn-success ml-3">Proceed to Orders Databse</a>
        </p>
    </div>
        <p>
            <a href="adminlogout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
        </p>    
</body>
</html>