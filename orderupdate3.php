<?php
// Include config file
require_once "../../config/config.php";
 
// Define variables and initialize with empty values
$status = $date = $owner_id = "";
$status_err = $date_err = $owner_id_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["order_id"]) && !empty($_POST["order_id"])){
    // Get hidden input value
    $order_id = $_POST["order_id"];

    // Validate status
    $input_status = trim($_POST["status"]);
    if(empty($input_status)){
        $status_err = "Please enter the status of the vehicle.";
    } elseif(!filter_var($input_status, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $status_err = "Please enter a valid status.";
    } else{
        $status = $input_status;
    }

    // Validate date
    $input_date = trim($_POST["date"]);
    if(empty($input_date)){
        $date_err = "Please enter the date";
    } elseif(!filter_var($input_date, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[a-zA-Z\s]*/")))){
        $date_err = "Please enter a valid date.";
    } else{
        $date = $input_date;
    }

    // Validate owner_id
    $input_owner_id = trim($_POST["owner_id"]);
    if(empty($input_owner_id)){
        $owner_id_err = "Please enter the Owner ID.";     
    } elseif(!ctype_digit($input_owner_id)){
        $owner_id_err = "Please enter a positive integer value.";
    } else{
        $owner_id = $input_owner_id;
    }

    
    // Check input errors before inserting in database
    if(empty($status_err) && empty($date_err) && empty($owner_id_err)){
        // Prepare an insert statement
        $sql = "UPDATE `orders` SET `status`= ?, `date`= ?, `owner_id` = ? WHERE order_id = ? ";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssii", $param_status, $param_date, $param_owner_id,$param_order_id);
            
            // Set parameters
            $param_status = $status;
            $param_date = $date;
            $param_owner_id = $owner_id;
            $param_order_id = $order_id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: ../order2.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["order_id"]) && !empty(trim($_GET["order_id"]))){
        // Get URL parameter
        $order_id =  trim($_GET["order_id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM `orders` WHERE order_id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_order_id);
            
            // Set parameters
            $param_order_id = $order_id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $status = $row["status"];
                    $date = $row["date"];
                    $owner_id = $row["owner_id"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: ordererror3.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
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
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{
            background: linear-gradient(to right, #ada996, #f2f2f2, #dbdbdb, #eaeaea);
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
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the order record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Status</label>
                            <input type="text" name="status" class="form-control <?php echo (!empty($status_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $status; ?>">
                            <span class="invalid-feedback"><?php echo $status_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <textarea name="date" class="form-control <?php echo (!empty($date_err)) ? 'is-invalid' : ''; ?>"><?php echo $date; ?></textarea>
                            <span class="invalid-feedback"><?php echo $date_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Owner_id</label>
                            <input type="text" name="owner_id" class="form-control <?php echo (!empty($owner_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $owner_id; ?>">
                            <span class="invalid-feedback"><?php echo $owner_id_err;?></span>
                        </div>
                        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="../order2.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>