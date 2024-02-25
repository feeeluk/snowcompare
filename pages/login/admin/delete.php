<?php

	// set the root folder (site root not server root) in relation to this page
	$path = "../../../";

	// every page can have a different title
	$page_title = "Manage comments";

	//connect to database_connection
	include($path."assets/includes/database_connection.php");
	
	// connect to site_details
	include($path."assets/includes/site_details.php");
	
	// if session is set
	if(isset($_SESSION['username']))
	{
		$username = $_SESSION['username'];

		// check to see if the user is an admin
		$query =  "SELECT is_admin FROM user WHERE username = '$username'";

		// run the query
		$result = mysqli_query($connection, $query) or die(mysqli_error($connection));

		if (mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				if($row['is_admin'] == 1)
				{
					// if product was selected
					if(isset($_GET['product']))
					{
						$product_id = $_GET['product'];

						// DELETE LINKED ROWS IN PRODUCT_IMAGE
						// create delete product images query
						$delete_product_images_query = "DELETE 
													FROM product_image 
													WHERE product_id IN (SELECT id 
													FROM product 
													WHERE id = '$product_id')";
						// run delete product images query
						$delete_product_images_result = mysqli_query($connection, $delete_product_images_query) or die(mysqli_error($connection));

						
						// DELETE LINKED ROWS IN POST
						// create delete product comments query						
						$delete_product_comments_query = "DELETE 
														FROM post 
														WHERE product_id IN (SELECT id 
														FROM product 
														WHERE id = '$product_id')";
						
						// DELETE PRODUCT
						// run delete products comments query
						$delete_product_comments_result = mysqli_query($connection, $delete_product_comments_query) or die(mysqli_error($connection));

						// create delete product query
						$delete_product_query = "DELETE
												FROM product
												WHERE id = '$product_id'";
						
						// run delete product query
						$delete_product_result = mysqli_query($connection, $delete_product_query) or die(mysqli_error($connection));;

						header('location: manage_products.php');
					}

					// if user was selected
					if(isset($_GET['user']) AND $_GET['user'] == $_SESSION['username'])
					{
						echo "Cannot delete the selected user as you are logged in!!!";
					}

					elseif(isset($_GET['user'])) 
					{
						$username = $_GET['user'];

						// DELETE LINKED ROWS IN POST
						// create delete post query
						$delete_user_post_query = "DELETE 
													FROM post 
													WHERE username IN (SELECT username 
													FROM user
													WHERE username = '$username')";

						// run delete post query
						$delete_user_post_result = mysqli_query($connection, $delete_user_post_query) or die(mysqli_error($connection));						

						// DELETE USER
						// create delete user query
						$delete_user_query = "DELETE FROM user
												WHERE username = '$username'";
												
						$delete_user_result = mysqli_query($connection, $delete_user_query) or die(mysqli_error($connection));

						header('location: manage_users.php');
					}

					// if comment was selected
					if(isset($_GET['comment']))
					{
						$comment_id = $_GET['comment'];

						// create delete comment query
						$delete_comment_query = "DELETE FROM post
												WHERE id = $comment_id";
						$delete_comment_result = mysqli_query($connection, $delete_comment_query) or die(mysqli_error($connection));

						header('location: manage_comments.php');
					}										
				}

				else
				{
					$error = "You must have administration rights to be able to add new products";	
				}
			}
		}

	}

	else
	{
		$error = "You must be logged in and have administration rights to be able to add new products";
	}


?>