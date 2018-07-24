<?php

//MOBILE API


header('Access-Control-Allow-Origin: *'); //Should work in Cross domain ajax Calling request


$mysql_hostname = "localhost";
$mysql_user = "shopawkr_insights";
$mysql_password = "Letsgoteam";
$mysql_database = "shopawkr_insights";

$con = mysqli_connect($mysql_hostname, $mysql_user,$mysql_password,$mysql_database); //website

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

if(isset($_GET['type']))
{
    if($_GET['type'] == "agentLogin")
	{
		$email = $_GET['email'];
		$password = $_GET['password'];
        
		//Create Query
		$query = "Select * from agents Where email='$email' and password='$password'";
		//Fire your Query against database
		$result1 = mysqli_query($con,$query);
		//get total no of rows from database according to the query
		$totalRows = mysqli_num_rows($result1);
		
		//Prepare Code for json format
		if($totalRows > 0)
		{
			$recipes = array();
            
			while($recipe = mysqli_fetch_array($result1, MYSQL_ASSOC))
			{
				$recipes[] = $recipe;
			}
			
			$output = json_encode($recipes);
                
			echo $output;
		}
        else
        {
            echo "Got Error While Logging In.";
        }
	}
        else if($_GET['type'] == "createOrder")
	{
        //get values
        $orderId = $_GET['orderId'];
        $clientEmail = $_GET['clientEmail'];
        

		//Create Query for Create Orders
		$qryCreateOrders = "INSERT INTO Orders (OrderId, ClientEmail, LatestStatus)
        VALUES ($orderId, $clientEmail, 'Created')";

        //Create Query for Orders History logs
        $qryInsertHistory = "INSERT INTO OrdersHistory (OrderId, OrderStatus)
        VALUES ($orderId, 'Created')";

        //Fire your Query against database
		if ($con->query($qryCreateOrders) === TRUE) {

            if($con->query($qryInsertHistory) === TRUE) {
                echo "New order created successfully";
            } else {
                echo "Error: " . $qryInsertHistory . "<br>" . $con->error;
            }
            
        } else {
            echo "Error: " . $qryCreateOrders . "<br>" . $con->error;
        }
    }    
        else if($_GET['type'] == "updateOrder")
	{
        //get values
        $orderId = $_GET['orderId'];
		$orderStatus = $_GET['orderStatus'];

		//Create Query
		$qryUpdateOrder = "UPDATE Orders SET OrderStatus=$orderStatus where OrderId=$orderId";
        //Create Query for Orders History logs
        $qryInsertHistory = "INSERT INTO OrdersHistory (OrderId, OrderStatus)
        VALUES ($orderId, $orderStatus)";

        //Fire your Query against database
		if ($con->query($qryUpdateOrder) === TRUE) {

            if($con->query($qryInsertHistory) === TRUE) {
                echo "Order updated successfully";
            } else {
                echo "Error: " . $qryInsertHistory . "<br>" . $con->error;
            }
            
        } else {
            echo "Error: " . $qryUpdateOrder . "<br>" . $con->error;
        }
    }    
        else if($_GET['type'] == "addItem") // this is one by one
	{
        //get values
        $orderId = $_GET['orderId'];
		$itemName = $_GET['itemName'];
		$qty = $_GET['qty'];
		$price = $_GET['price'];
		$notes = $_GET['notes'];
		$link = $_GET['link'];

		//Create Query for Create Orders
		$qryAddItem = "INSERT INTO OrderItems (OrderId, ItemName, Qty, Price, Notes, Link)
        VALUES ($orderId, $itemName, $qty, $price, $notes, $link)";

        //Fire your Query against database
		if ($con->query($qryAddItem) === TRUE) {
            echo "Successfully Added New Item";            
        } else {
            echo "Error: " . $qryAddItem . "<br>" . $con->error;
        }
	}
     
    
}
?>