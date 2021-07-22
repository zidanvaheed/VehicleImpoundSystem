<?php
// Include config file
require_once "../config/config.php";
 
// Define variables and initialize with empty values
$vehicle_name = $condition = $price = $numberplate = "";
$vehicle_name_err = $condition_err = $price_err = $numberplate_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["vehicle_id"]) && !empty($_POST["vehicle_id"])){
    // Get hidden input value
    $vehicle_id = $_POST["vehicle_id"];
    
    // Validate name
    $input_vehicle_name = trim($_POST["vehicle_name"]);
    if(empty($input_vehicle_name)){
        $vehicle_name_err = "Please enter a name.";
    } elseif(!filter_var($input_vehicle_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[a-zA-Z\s]*/")))){
        $vehicle_name_err = "Please enter a valid name.";
    } else{
        $vehicle_name = $input_vehicle_name;
    }
    
    // Validate numberplate
    $input_numberplate = trim($_POST["NumberPlate"]);
    if(empty($input_numberplate)){
        $numberplate_err = "Please enter the NumberPlate of the vehicle.";
    } elseif(!filter_var($input_numberplate, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[a-zA-Z\s]*/")))){
        $numberplate_err = "Please enter a valid numberplate.";
    } else{
        $numberplate = $input_numberplate;
    }
    
    // Validate condition
    $input_condition = trim($_POST["Condition"]);
    if(empty($input_condition)){
        $condition_err = "Please enter the Condition of the vehicle.";
    } elseif(!filter_var($input_condition, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $condition_err = "Please enter a valid condition.";
    } else{
        $condition = $input_condition;
    }
 
    // Validate price
    $input_price = trim($_POST["Price"]);
    if(empty($input_price)){
        $price_err = "Please enter the Price.";     
    } elseif(!ctype_digit($input_price)){
        $price_err = "Please enter a positive integer value.";
    } else{
        $price = $input_price;
    }
    
    
    // Check input errors before inserting in database
    if(empty($vehicle_name_err) && empty($numberplate_err) && empty($condition_err) && empty($price_err)){
        // Prepare an update statement
        $sql = "UPDATE `vehicle` SET `vehicle_name`= ?, `NumberPlate`= ?, `Condition` = ?, `Price` = ? WHERE vehicle_id = ? ";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssii", $param_vehicle_name, $param_numberplate, $param_condition, $param_price,$param_vehicle_id);
            
            // Set parameters
            $param_vehicle_name = $vehicle_name;
            $param_numberplate = $numberplate;
            $param_condition = $condition;
            $param_price = $price;
            $param_vehicle_id = $vehicle_id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index3.php");
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
    if(isset($_GET["vehicle_id"]) && !empty(trim($_GET["vehicle_id"]))){
        // Get URL parameter
        $vehicle_id =  trim($_GET["vehicle_id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM `vehicle` WHERE vehicle_id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_vehicle_id);
            
            // Set parameters
            $param_vehicle_id = $vehicle_id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $vehicle_name = $row["vehicle_name"];
                    $numberplate = $row["NumberPlate"];
                    $condition = $row["Condition"];
                    $price = $row["Price"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error3.php");
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
        header("location: error3.php");
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
                    <p>Please edit the input values and submit to update the vehicle record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Vehicle Name</label>
                            <input type="text" name="vehicle_name" class="form-control <?php echo (!empty($vehicle_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $vehicle_name; ?>">
                            <span class="invalid-feedback"><?php echo $vehicle_name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Number Plate</label>
                            <textarea name="NumberPlate" class="form-control <?php echo (!empty($numberplate_err)) ? 'is-invalid' : ''; ?>"><?php echo $numberplate; ?></textarea>
                            <span class="invalid-feedback"><?php echo $numberplate_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Condition</label>
                            <input type="text" name="Condition" class="form-control <?php echo (!empty($condition_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $condition; ?>">
                            <span class="invalid-feedback"><?php echo $condition_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" name="Price" class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $price; ?>">
                            <span class="invalid-feedback"><?php echo $price_err;?></span>
                        </div>
                        <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index3.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>