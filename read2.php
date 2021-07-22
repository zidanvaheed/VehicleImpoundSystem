<?php
// Check existence of id parameter before processing further
if(isset($_GET["owner_id"]) && !empty(trim($_GET["owner_id"]))){
    // Include config file
    require_once "../config/config.php";
    
    // Prepare a select statement
    $sql = "SELECT o.name, v.vehicle_id, v.vehicle_name, v.NumberPlate, v.Condition, v.Price FROM `vehicle` AS v INNER JOIN `owner` AS o ON v.vehicle_id = o.vehicle_id WHERE o.owner_id = ? ";
    //SELECT o.name, v.vehicle_name, v.NumberPlate, v.Condition, v.Price FROM `vehicle` AS v, `owner` AS o WHERE v.vehicle_id = o.vehicle_id AND o.owner_id = "?"
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_owner_id);
        
        // Set parameters
        $param_owner_id = trim($_GET["owner_id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $name = $row["name"];
                $vehicle_id = $row["vehicle_id"];
                $vehiclename = $row["vehicle_name"];
                $NumberPlate = $row["NumberPlate"];
                $Condition = $row["Condition"];
                $Price = $row["Price"];
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error2.php");
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
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error2.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{
            background: linear-gradient(to right, #9796f0, #fbc7d4);
        }
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
        .form-button{
            float: left;
            margin-right:5px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3">Detailed Record</h1>
                    <div class="form-group">
                        <label>Name</label>
                        <p><b><?php echo $row["name"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Vehicle ID</label>
                        <p><b><?php echo $row["vehicle_id"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Vehicle</label>
                        <p><b><?php echo $row["vehicle_name"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Number Plate</label>
                        <p><b><?php echo $row["NumberPlate"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Vehicle Condition</label>
                        <p><b><?php echo $row["Condition"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Cost</label>
                        <p><b><?php echo $row["Price"]; ?></b></p>
                    </div>
                    <div class="form-button">
                        <p><a href="index2.php" class="btn btn-primary" >Back&nbsp;&nbsp;&nbsp;&nbsp;</a></p> 
                        <?php
                        echo '<a href="update1.php?vehicle_id='. $row['vehicle_id'] .'" class="btn btn-danger">Update</a>';
                        ?>
                    </div>              
                </div>
            </div>        
        </div>
    </div>
</body>
</html>