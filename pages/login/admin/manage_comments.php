<?php
	// set the root folder (site root not server root) in relation to this page
	$path = "../../../";

	// every page can have a different title
	$page_title = "Manage comments";

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

				$comment_query = "SELECT id, comment, post_time, username, product_id FROM post ORDER BY id DESC";
				$comment_result = mysqli_query($connection, $comment_query);

				echo "<table>
					<th width='10%' >ID</th>
					<th width='70%' >Comment</th>
					<th width='20%' ></th>
					";

				while($comment_row = mysqli_fetch_array($comment_result, MYSQLI_ASSOC))
				{
					echo "<tr>
							<td>".$comment_row['id']."</td>
							<td>".$comment_row['comment']."</td>
							<td><a href='edit_comment.php?comment=".$comment_row['id']."'>edit</a> | <a onclick=\"return confirm('Are you sure?')\" href='delete.php?comment=".$comment_row['id']."'>delete</a></td>
						</tr>
						";
				}

				echo "</table>";	

				// end of the check is admin section
				}

				else
				{
					echo "You must have administration rights to be able to add new products";	
				}
			}
		}

	}

	else
	{
		echo "You must be logged in and have administration rights to be able to add new products";
	}


	//login include
	include($path."assets/includes/login.php");

	//footer include
	include($path."assets/includes/footer.php");

?>