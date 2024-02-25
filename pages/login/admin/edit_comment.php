<?php
	// set the root folder (site root not server root) in relation to this page
	$path = "../../../";

	// every page can have a different title
	$page_title = "Edit comment".$_GET['comment'];

	// connect to db
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
		$result = mysqli_query($connection, $query) or die(mysqli_error($connection)); 

		if (mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				if($row['is_admin'] == 1)
				{
					// store value from url
					$post_id = mysqli_real_escape_string($connection, strip_tags($_GET['comment']));

					if(isset($_GET['submit']))
					{
						$updated_comment = mysqli_real_escape_string($connection, strip_tags(trim($_GET['updated_comment'])));

						$update_query = "UPDATE post SET comment = '$updated_comment' WHERE id = $post_id";

						$update_result = mysqli_query($connection, $update_query);

						echo "<script type=\"text/javascript\">document.location.href=\"manage_comments.php\";</script>";
					}

					else
					{
						// create post query
						$query = "SELECT * FROM post WHERE id = '$post_id'";

						// run post query
						$result = mysqli_query($connection, $query) or die(mysqli_error($connection));

						if(mysqli_num_rows($result) > 0)
						{
							while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
							{
								echo "<form action='edit_comment.php' method='get'>
								<p>
								<span class='xs'>Username: </span>".$row['username']."<br />
								<span class='xs'>Product id: </span>".$row['product_id']."<br />
								<span class='xs'>Manufacturer id: </span>".$row['manufacturer_id']."<br />
								<span class='xs'>Post time: </span>".$row['post_time']."<br />
								<span class='xs top'>Comment: </span><textarea name='updated_comment' id='updated_comment' rows='20' cols='50'>".$row['comment']."</textarea><br />
								<span class='xs'>&nbsp;</span>
								<input type='submit' name='submit' id='submit' value='Update'>
								<input type='hidden' name='comment' id='comment' value='".$post_id."'>
								<a href='manage_comments.php'>Cancel</a>
								</p>
								</form>";
							}
						}
					}	
				}

				else
				{
					echo "You must have admin priviledges";
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
