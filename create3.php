<?php
// Include config file
require_once "../config/config.php";
 
// Define variables and initialize with empty values
$vehiclename = $numberplate = $condition = $price = "";
$vehiclename_err = $numberplate_err = $condition_err = $price_err =  "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_vehiclename = trim($_POST["vehicle_name"]);
    if(empty($input_vehiclename)){
        $vehiclename_err = "Please enter the name of the vehicle.";
    } elseif(!filter_var($input_vehiclename, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[a-zA-Z\s]*/")))){
        $vehiclename_err = "Please enter a valid name.";
    } else{
        $vehiclename = $input_vehiclename;
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
    if(empty($vehiclename_err) && empty($numberplate_err) && empty($condition_err) && empty($price_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO `vehicle`(`vehicle_name`, `NumberPlate`, `Condition`, `Price`) VALUES (?,?,?,?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_vehiclename, $param_numberplate, $param_condition, $param_price);
            
            // Set parameters
            $param_vehiclename = $vehiclename;
            $param_numberplate = $numberplate;
            $param_condition = $condition;
            $param_price = $price;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Go to next insertion page
                header("location: create4.php");
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
                    <h2 class="mt-5">Create Vehicle Record</h2>
                    <p>Fill this form and submit to add vehicle record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Vehicle Name</label>
                            <input type="text" name="vehicle_name" class="form-control <?php echo (!empty($vehiclename_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $vehiclename; ?>">
                            <span class="invalid-feedback"><?php echo $vehiclename_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>NumberPlate</label>
                            <input type="text" name="NumberPlate" class="form-control <?php echo (!empty($numberplate_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $numberplate; ?>">
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
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index3.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>