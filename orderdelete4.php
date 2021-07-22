<?php
// Process delete operation after confirmation
if(isset($_POST["item_id"]) && !empty($_POST["item_id"])){
    // Include config file
    require_once "../../config/config.php";
    
    // Prepare a delete statement
    $sql = "DELETE FROM `order_items` WHERE item_id = ? ";

    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_item_id);
        
        // Set parameters
        $param_item_id = trim($_POST["item_id"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Records deleted successfully. Redirect to landing page
            header("location: ../order2.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statements
    mysqli_stmt_close($stmt);
    
    // Close connections
    mysqli_close($link);

} else{
    // Check existence of id parameter
    if(empty(trim($_GET["item_id"]))){
        // URL doesn't contain id parameter. Redirect to error page
        header("location: ordererror3.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{
            background: linear-gradient(to right, #16a085, #f4d03f); 
        }
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">Delete Record</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="item_id" value="<?php echo trim($_GET["item_id"]); ?>"/>
                            <p>Are you sure you want to delete this order record?</p>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="../order2.php" class="btn btn-secondary">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>