<?php
	// set the root folder (site root not server root) in relation to this page
	$path = "../../";

	// every page can have a different title
	$page_title = "Contact";

	// connect to database_connection
	include($path."assets/includes/database_connection.php");

	// connect to site_details
	include($path."assets/includes/site_details.php");
	

	//header include
	include($path."assets/includes/header.php");
?>					

<h5>Let us know how we are doing by getting in touch.</h5>


<?php
	// display form if user has not clicked submit
	if (!isset($_POST["submit"]))
	{

?>
	<form method="post" action="contact.php">

		<p>
			<span class="xxs">From:</span>
			<input type="text" name="from">
		</p>

		<p>
			<span class="xxs">Subject:</span>
			<input type="text" name="subject">
		</p>

		<p>
			<span class="xxs top">Message:</span>
			<textarea rows="10" cols="40" name="message"></textarea>
		</p>

		<p>
			<span class="xxs top">&nbsp;</span>
			<input type="submit" name="submit" value="Submit Feedback">
			<a href="<?php echo $path ?>index.php">Cancel</a>
		</p>

	</form>

<?php 
	}

	else
	// the user has submitted the form
	{
		// Check if the "from" input field is filled out
		if (isset($_POST["from"]))
		{
		$from = strip_tags(ucfirst(trim($_POST["from"]))); // sender
		$subject = strip_tags(ucfirst(trim($_POST["subject"])));
		$message = strip_tags(ucfirst(trim($_POST["message"])));
		// message lines should not exceed 70 characters (PHP rule), so wrap it
		$message = wordwrap($message, 70);

		// send mail
		mail("philip.henning.1@city.ac.uk", $subject, $message, "From: $from\r\n");
		echo "<p>Thank you for sending us feedback</p>";
		}
	}
?>

<?php
	//login include
	include($path."assets/includes/login.php");

	//footer include
	include($path."assets/includes/footer.php");
?>
