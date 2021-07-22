<?php
// Include config file
require_once "../../config/config.php";
 
// Define variables and initialize with empty values
$status = $date = $owner_id = "";
$status_err = $date_err = $owner_id_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
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
        $sql = "INSERT INTO `orders`(`status`, `date`, `owner_id`) VALUES (?,?,?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssi", $param_status, $param_date, $param_owner_id);
            
            // Set parameters
            $param_status = $status;
            $param_date = $date;
            $param_owner_id = $owner_id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Go to next insertion page
                header("location: createorder1.php");
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
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{
            font: sans-serif;
            color: white;
            background: linear-gradient(to right, #1f4037, #99f2c8); 
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
                    <h2 class="mt-5">Place New Order</h2>
                    <p>Fill this form and submit to add Order record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Status</label>
                            <input type="text" name="status" class="form-control <?php echo (!empty($status_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $status; ?>">
                            <span class="invalid-feedback"><?php echo $status_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <input type="text" name="date" class="form-control <?php echo (!empty($date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $date; ?>">
                            <span class="invalid-feedback"><?php echo $date_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Owner_ID</label>
                            <input type="text" name="owner_id" class="form-control <?php echo (!empty($owner_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $owner_id; ?>">
                            <span class="invalid-feedback"><?php echo $owner_id_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="../order.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>