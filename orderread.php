<?php
// Check existence of id parameter before processing further
if(isset($_GET["order_id"]) && !empty(trim($_GET["order_id"]))){
    // Include config file
    require_once "../../config/config.php";
    
    // Prepare a select statement
    $sql = "SELECT o.order_id, i.item_id, i.item_name, i.quantity, i.vehicle_id FROM `orders` AS o INNER JOIN `order_items` AS i ON o.order_id = i.item_id WHERE o.order_id = ? ";
    //SELECT o.name, v.vehicle_name, v.NumberPlate, v.Condition, v.Price FROM `vehicle` AS v, `owner` AS o WHERE v.vehicle_id = o.vehicle_id AND o.owner_id = "?"
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_order_id);
        
        // Set parameters
        $param_order_id = trim($_GET["order_id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $order_id = $row["order_id"];
                $item_id = $row["item_id"];
                $item_name = $row["item_name"];
                $quantity = $row["quantity"];
                $vehicle_id = $row["vehicle_id"];
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: ordererror.php");
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
    header("location: ordererror.php");
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
                        <label>Order ID</label>
                        <p><b><?php echo $row["order_id"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Item ID</label>
                        <p><b><?php echo $row["item_id"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Item Name</label>
                        <p><b><?php echo $row["item_name"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <p><b><?php echo $row["quantity"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Vehicle ID</label>
                        <p><b><?php echo $row["vehicle_id"]; ?></b></p>
                    </div>
                    <div class="form-button">
                        <p><a href="../order.php" class="btn btn-primary" >Back&nbsp;&nbsp;&nbsp;&nbsp;</a></p> 
                        <?php
                        echo '<a href="orderdelete1.php?item_id='. $row['item_id'] .'" class="btn btn-danger">Delete</a>';
                        ?>
                        <?php
                        echo '<a href="orderupdate1.php?item_id='. $row['item_id'] .'" class="btn btn-danger">Update</a>';
                        ?>
                    </div>              
                </div>
            </div>        
        </div>
    </div>
</body>
</html>