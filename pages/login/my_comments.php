<?php
	// set the root folder (site root not server root) in relation to this page
	$path = "../../";	

	// every page can have a different title
	$page_title = "Comments";

	//connect to database_connection
	include($path."assets/includes/database_connection.php");
	
	// connect to site_details
	include($path."assets/includes/site_details.php");	

	//header include
	include($path."assets/includes/header.php");						

	if(isset($_SESSION['username']))
	{
	//show all comments posted by the user
		$username = $_SESSION['username'];

		$comment_query = "SELECT id, post_time, comment
						FROM post
						WHERE username = '$username'
						ORDER BY id DESC";

		$comment_result = mysqli_query($connection, $comment_query);

		echo "<p><b>
			<span class='xxs'>Post id</span>
			<span class='xs'>Time of post</span>
			<span class='m'>Comment</span>
			</b></p>
			";

		while($comment_row = mysqli_fetch_array($comment_result, MYSQLI_ASSOC))
		{
			echo "<p>
				<span class='xxs'>".$comment_row['id']."</span>
				<span class='xs'>".$comment_row['post_time']."</span>
				<span class='m'>\"".$comment_row['comment']."\"</span>
				<span class='xxs'><a href='edit_my_comment.php?comment=".$comment_row['id']."'>edit</a> | <a href='edit_my_comment.php?delete=true&comment=".$comment_row['id']."'>delete</a></span>
				</p>
				";
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
