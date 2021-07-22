<?php
// Include config file
require_once "../config/config.php";
 
// Define variables and initialize with empty values
$name = $address = $ph_no = $email = $vehicle_id = "";
$name_err = $address_err = $ph_no_err = $email_err = $vehicle_id_err =  "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[a-zA-Z\s]*/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }

    // Validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter the address.";
    } elseif(!filter_var($input_address, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[a-zA-Z\s]*/")))){
        $address_err = "Please enter a valid address.";
    } else{
        $address = $input_address;
    }
    
    // Validate phone number
    $input_ph_no = trim($_POST["ph_no"]);
    if(empty($input_ph_no)){
        $ph_no_err = "Please enter the phone number.";     
    } elseif(!ctype_digit($input_ph_no)){
        $ph_no_err = "Please enter positive integer values.";
    } else{
        $ph_no = $input_ph_no;
    }
 
    // Validate email
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Please enter the email id.";     
    } else{
        $email = $input_email;
    }
    
    // Validate vehicle id
    $input_vehicle_id = trim($_POST["vehicle_id"]);
    if(empty($input_vehicle_id)){
        $vehicle_id_err = "Please enter the vehicle id.";     
    } elseif(!ctype_digit($input_vehicle_id)){
        $vehicle_id_err = "Please enter a positive integer value.";
    } else{
        $vehicle_id = $input_vehicle_id;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($ph_no_err) && empty($email_err) && empty($vehicle_id_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO `owner`(`name`, `address`, `ph_no`, `email`, `vehicle_id`) VALUES (?,?,?,?,?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssisi", $param_name, $param_address, $param_ph_no, $param_email, $param_vehicle_id);
            
            // Set parameters
            $param_name = $name;
            $param_address = $address;
            $param_ph_no = $ph_no;
            $param_email = $email;
            $param_vehicle_id = $vehicle_id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index2.php");
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
                    <h2 class="mt-5">Create Record</h2>
                    <p>Fill this form and submit to add owner record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $address; ?>">
                            <span class="invalid-feedback"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="ph_no" class="form-control <?php echo (!empty($ph_no_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $ph_no; ?>">
                            <span class="invalid-feedback"><?php echo $ph_no_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <textarea name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>"><?php echo $email; ?></textarea>
                            <span class="invalid-feedback"><?php echo $email_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Vehicle ID</label>
                            <input type="text" name="vehicle_id" class="form-control <?php echo (!empty($vehicle_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $vehicle_id; ?>">
                            <span class="invalid-feedback"><?php echo $vehicle_id_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index2.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>