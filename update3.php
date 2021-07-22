<?php
// Include config file
require_once "../config/config.php";
 
// Define variables and initialize with empty values
$name = $address = $email = $ph_no = "";
$name_err = $address_err = $email_err = $ph_no_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["owner_id"]) && !empty($_POST["owner_id"])){
    // Get hidden input value
    $owner_id = $_POST["owner_id"];
    
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
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
        $email_err = "Please enter the email.";     
    } else{
        $email = $input_email;
    }

    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($ph_no_err) && empty($email_err)){
        // Prepare an update statement
        $sql = "UPDATE `owner` SET `name`= ?, `address`= ?, `ph_no` = ?, `email` = ? WHERE owner_id = ? ";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssisi", $param_name, $param_address, $param_ph_no, $param_email, $param_owner_id);
            
            // Set parameters
            $param_name = $name;
            $param_address = $address;
            $param_ph_no = $ph_no;
            $param_email = $email;
            $param_owner_id = $owner_id;
            
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
}else{
    // Check existence of id parameter before processing further
    if(isset($_GET["owner_id"]) && !empty(trim($_GET["owner_id"]))){
        // Get URL parameter
        $owner_id =  trim($_GET["owner_id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM `owner` WHERE owner_id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_owner_id);
            
            // Set parameters
            $param_owner_id = $owner_id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $name = $row["name"];
                    $address = $row["address"];
                    $ph_no = $row["ph_no"];
                    $email = $row["email"];
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
                    <p>Please edit the input values and submit to update the owner record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></textarea>
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
                        <input type="hidden" name="owner_id" value="<?php echo $owner_id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index3.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>