<?php
	// set the root folder (site root not server root) in relation to this page
	$path = "../../";

	//connect to database_connection
	include($path."assets/includes/database_connection.php");	

	//assign the form values to variables
	$usern = mysqli_real_escape_string($connection, strip_tags($_POST['username']));
	$comment = mysqli_real_escape_string($connection, strip_tags(trim($_POST['comment'])));
	$product_id = mysqli_real_escape_string($connection, strip_tags($_POST['product_id']));
	$manufacturer_id = mysqli_real_escape_string($connection, strip_tags($_POST['manufacturer_id']));
	$time = date("Y-m-d H:i:s");
	 
    
    //store the sql query as a variable
	$query =  "INSERT INTO post (username, product_id, manufacturer_id, post_time, comment)
				VALUES 
				('$usern', '$product_id', $manufacturer_id, '$time', '$comment' )";

	// run the query - if successful - go back to page
    if (mysqli_query($connection, $query))
        {
			header("location: ".$_SERVER['HTTP_REFERER']);
    	}

	else
		{
			echo "There was an error submitting this comment<br />";
		}			
?>