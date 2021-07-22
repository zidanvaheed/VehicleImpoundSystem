<?php
// Include config file
require_once "../../config/config.php";
 
// Define variables and initialize with empty values
$item_id = $item_name = $quantity = $vehicle_id = "";
$item_id_err = $item_name_err = $quantity_err = $vehicle_id_err =  "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate ID
    $input_item_id = trim($_POST["item_id"]);
    if(empty($input_item_id)){
        $item_id_err = "Please enter the ID.";     
    } elseif(!ctype_digit($input_item_id)){
        $item_id_err = "Please enter a positive integer value.";
    } else{
        $item_id = $input_item_id;
    }

    // Validate item name
    $input_item_name = trim($_POST["item_name"]);
    if(empty($input_item_name)){
        $item_name_err = "Please enter the Name of the Item.";
    } elseif(!filter_var($input_item_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[a-zA-Z\s]*/")))){
        $item_name_err = "Please enter a valid Item name.";
    } else{
        $item_name = $input_item_name;
    }
    
    // Validate quantity
    $input_quantity = trim($_POST["quantity"]);
    if(empty($input_quantity)){
        $quantity_err = "Please enter the Quantity.";     
    } elseif(!ctype_digit($input_quantity)){
        $quantity_err = "Please enter a positive integer value.";
    } else{
        $quantity = $input_quantity;
    }
 
    // Validate vehicle_id
    $input_vehicle_id = trim($_POST["vehicle_id"]);
    if(empty($input_vehicle_id)){
        $vehicle_id_err = "Please enter the Vehicle ID.";     
    } elseif(!ctype_digit($input_vehicle_id)){
        $vehicle_id_err = "Please enter a positive integer value.";
    } else{
        $vehicle_id = $input_vehicle_id;
    }
    
    // Check input errors before inserting in database
    if(empty($item_id_err) && empty($item_name_err) && empty($quantity_err) && empty($vehicle_id_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO `order_items`(`item_id`, `item_name`, `quantity`, `vehicle_id`) VALUES (?,?,?,?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "isii", $param_item_id, $param_item_name, $param_quantity, $param_vehicle_id);
            
            // Set parameters
            $param_item_id = $item_id;
            $param_item_name = $item_name;
            $param_quantity = $quantity;
            $param_vehicle_id = $vehicle_id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Go to next insertion page
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
                            <label>Item ID</label>
                            <input type="text" name="item_id" class="form-control <?php echo (!empty($item_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $item_id; ?>">
                            <span class="invalid-feedback"><?php echo $item_id_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Item Name</label>
                            <input type="text" name="item_name" class="form-control <?php echo (!empty($item_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $item_name; ?>">
                            <span class="invalid-feedback"><?php echo $item_name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="text" name="quantity" class="form-control <?php echo (!empty($quantity_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $quantity; ?>">
                            <span class="invalid-feedback"><?php echo $quantity_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Vehicle ID</label>
                            <input type="text" name="vehicle_id" class="form-control <?php echo (!empty($vehicle_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $vehicle_id; ?>">
                            <span class="invalid-feedback"><?php echo $vehicle_id_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="../order2.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>