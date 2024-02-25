<?php
	// set the root folder (site root not server root) in relation to this page
	$path = "../../../";

	// every page can have a different title
	$page_title = "Site statistics";

	//connect to database_connection
	include($path."assets/includes/database_connection.php");
	
	// connect to site_details
	include($path."assets/includes/site_details.php");	

	//header include
	include($path."assets/includes/header.php");						

	// if session is set
	if(isset($_SESSION['username']))
	{
	
		$username = $_SESSION['username'];

		// check to see if the user is an admin
		$query =  "SELECT is_admin FROM user WHERE username = '$username'";

		// run the query
		$result = mysqli_query($connection, $query); 

		if (mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				if($row['is_admin'] == 1)
				{
					//number of products
					$products_query = "SELECT count(id) AS product_count FROM product";
					$products_result = mysqli_query($connection, $products_query);

					while($products_row = mysqli_fetch_array($products_result, MYSQLI_ASSOC))
					{
						echo "<span class='xs'>products: </span>".$products_row['product_count']."<br />";
					}

					//number of manufacturers
					$manufacturers_query = "SELECT count(id) AS manufacturer_count FROM manufacturer";
					$manufacturers_result = mysqli_query($connection, $manufacturers_query);

					while($manufacturers_row = mysqli_fetch_array($manufacturers_result, MYSQLI_ASSOC))
					{
						echo "<span class='xs'>manufacturers: </span>".$manufacturers_row['manufacturer_count']."<br />";
					}


					//number of product types
					$types_query = "SELECT count(id) AS type_count FROM type";
					$types_result = mysqli_query($connection, $types_query);

					while($types_row = mysqli_fetch_array($types_result, MYSQLI_ASSOC))
					{
						echo "<span class='xs'>product types: </span>".$types_row['type_count']."<br />";
					}

					//number of products for each type

					// number of comments

					// number of users who have commented

					// number of users who haven't commented

					// product with most number of comments

					// number of users
					$users_query = "SELECT count(username) AS user_count FROM user";
					$users_result = mysqli_query($connection, $users_query);

					while($users_row = mysqli_fetch_array($users_result, MYSQLI_ASSOC))
					{
						echo "<span class='xs'>users: </span>".$users_row['user_count']."<br />";
					}

					// number of users who are admins

					// newest user

					// oldest user

					// most active user
				}

				else
				{
					echo "You must have admin priviledges to see site statistics";
				}
			}
		}
	}
	
	else
	{
		echo "<p>You must be logged in and have admin privileges to be able so view this page</p>";
	}

	//login include
	include($path."assets/includes/login.php");

	//footer include
	include($path."assets/includes/footer.php");
?>
