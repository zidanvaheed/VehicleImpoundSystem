<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders Database</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body{
            background: linear-gradient(to right, #c6ffdd, #fbd786, #f7797d);
        }
        .wrapper{
            width: 800px;
            margin: 0 auto;
        }
        table.table-bordered{
            border:1px solid black;
            margin-top:20px;
        }
        table.table-bordered > thead > tr > th{
            border:1px solid black;
        }
        table.table-bordered > tbody > tr > td{
            border:1px solid black;
        }
        table tr td:last-child{
            width: 120px;
        }
        .logout{
            float: right;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="logout">
        <a href="../admin/adminwelcome.php" class="btn btn-danger ml-3">Leave Database</a>     
    </div>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Order Detials</h2>
                        <a href="ordercrud/createorder.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Place a New Order</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "../config/config.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM `orders`";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-hover">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Order ID</th>";
                                        echo "<th>Status</th>";
                                        echo "<th>Date</th>";
                                        echo "<th>Owner ID</th>";
                                        echo "<th>Actions</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['order_id'] . "</td>";
                                        echo "<td>" . $row['status'] . "</td>";
                                        echo "<td>" . $row['date'] . "</td>";
                                        echo "<td>" . $row['owner_id'] . "</td>";
                                        echo "<td>";
                                            echo '<a href="ordercrud/orderread.php?order_id='. $row['order_id'] .'" class="mr-3" title="View Order" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="ordercrud/orderupdate.php?order_id='. $row['order_id'] .'" class="mr-3" title="Update Order" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="ordercrud/orderdelete.php?order_id='. $row['order_id'] .'" class="mr-3 "title="Delete Order" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                        //&amp;vehicle_id='. $row['vehicle_id'] .' - use if you need vehicle id for passing.
                                        //?owner_id='. $row['owner_id'] .' - use for owner id passing for any actions.
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
 
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>