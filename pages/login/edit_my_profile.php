<?php
	// set the root folder (site root not server root) in relation to this page
	$path = "../../";	

	// every page can have a different title
	$page_title = "Update my details";

	//connect to database_connection
	include($path."assets/includes/database_connection.php");
	
	// connect to site_details
	include($path."assets/includes/site_details.php");	

	//header include
	include($path."assets/includes/header.php");

	// initialise variables. These are used to populate the form fields
	$usern =$_SESSION['username'];
	$title_array = array("Mr", "Mrs", "Miss", "Mz");
	$gender_array = array("Male", "Female");
	$month_array = array(
		"January" => "01",
		"February" => "02",
		"March" => "03",
		"April" => "04",
		"May" => "05",
		"June" => "06",
		"July" => "07",
		"August" => "08",
		"September" => "09",
		"October" => "10",
		"November" => "11",
		"December" => "12"
		);
	$admin_password = "pete";

	// store user details query as variable
	$query = "SELECT * FROM user WHERE username = '".$usern."' ";

	// run query
	$result = mysqli_query($connection, $query);

	if(mysqli_num_rows($result) > 0)
	{
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				$passw = $row['password'];
				$passw_check = $row['password'];
				$title = $row['title'];
				$first_name = $row['first_name'];
				$last_name = $row['last_name'];
				$gender = $row['gender'];
				$email = $row['email'];
				$dob = strtotime($row['dob']);
				$image = $row['image'];
				$admin = $row['is_admin'];
				// break dob into different parts
				$selected_day =date('d', $dob);
				$selected_month =date('m', $dob);
				$selected_year =date('Y', $dob);
			}
	}

	// variables to store validation. True for valid, false for invalid. These are intentionally set as false, so that the form must validate correctly before it will send. Each form input has a check to see if the corresponding variable is true or false (which is only tested AFTER the form has been submitted).
	$not_empty_password = false;
	$password_match =false;
	$not_empty_first_name = false;
	$not_empty_last_name = false;
	$not_empty_email = false;
	$valid_email = false;
	$valid_date =false;
	$valid_admin =0;

	// variables that store error messages
	$required = "<span class='error'>Required</span>";
	$invalid = "<span class='error'>Invalid</span>";
	$not_matching = "<span class='error'>Don't match</span>";

	// if the delete image link was clicked
	if(isset($_GET['delete']))
		{
			// delete image query
			$delete_query = "UPDATE user
							SET
							image = 'images/users/placeholder.png'
							WHERE username = '".$usern."'";
			
			mysqli_query($connection, $delete_query);
			echo "<script>window.location.assign('".$path."pages/login/edit_my_profile.php');</script>";
		}	

	// if the submit button has been clicked 
	if(isset($_POST['submit']))
		{
		// assign form input values to the corresponding variables. 'selected' variables are used to store the currently selected option of drop down fields, so that the selected option will be maintained when the form is refreshed
			$passw = mysqli_real_escape_string($connection, strip_tags(trim($_POST['passw'])));
			$passw_check = mysqli_real_escape_string($connection, strip_tags(trim($_POST['passw_check'])));
			$selected_title = mysqli_real_escape_string($connection, strip_tags($_POST['title']));
			$first_name = mysqli_real_escape_string($connection, strip_tags(ucfirst(trim($_POST['first_name']))));
			$last_name = mysqli_real_escape_string($connection, strip_tags(ucfirst(trim($_POST['last_name']))));
			$selected_gender = mysqli_real_escape_string($connection, strip_tags($_POST['gender']));
			$email = mysqli_real_escape_string($connection, strip_tags(trim($_POST['email'])));
			$selected_day = mysqli_real_escape_string($connection, strip_tags($_POST['day']));
			$selected_month = mysqli_real_escape_string($connection, strip_tags($_POST['month']));
			$selected_year = mysqli_real_escape_string($connection, strip_tags($_POST['year']));
			$dob = $selected_year."-".$month_array[$selected_month]."-".$selected_day;
			$today = date("Y-m-d");
 
		//validate the form

		// 1. if the password address field is not empty then set the empty_email as true
			if($passw !="")
				{
					$not_empty_password = true;
				}

		// 2. if the passwords match set the valid_password variable to true
			if($passw == $passw_check)
				{
					$password_match = true;
				}

		// 3. if the first_name address field is not empty then set the empty_email as true
			if($first_name !="")
				{
					$not_empty_first_name = true;
				}

		// 4. if the last_name address field is not empty then set the empty_email as true
			if($last_name !="")
				{
					$not_empty_last_name = true;
				}

		// 5. if the email address field is not empty then set the empty_email as true
			if($email !="")
				{
					$not_empty_email = true;
				}

		// 6. if the email address format is correct then set the valid_email variable to true
			if(filter_var($email, FILTER_VALIDATE_EMAIL))
				{
					$valid_email = true;
				}
			
			// parse the results of the form so that they are in a date format recognised by the checkdate() function. The date_parse_from_fromat() function stores results in array (in this case we have saved the array as a variable)
				$date_array = date_parse_from_format("j.F.Y", $selected_day.".".$selected_month.".".$selected_year);				
			
			// build the checkdate() argument by refering to the associative array data contained within $date_array
				$date_result = checkdate($date_array["month"], $date_array["day"], $date_array["year"]);

		// 7. if the date is valid then set the valid_date variable to true - checkdate() returns a value of 1 if the date is valid
			if($date_result == 1)
				{
					$valid_date = true;
				}

		// if the admin_password is correctly entered, set admin as 1. If the user is already an admin then ignore this step
			if($admin == 0)
				{
					$admin_password_check = $_POST['admin'];
					if($admin_password_check == $admin_password)
						{
							$admin = 1;
						}					
				}
		// form submit validation
			// if all of the required input fields contain valid content then the form is complete 	
			if ($not_empty_password == true AND $password_match == true AND $not_empty_first_name == true AND $not_empty_last_name == true AND $not_empty_email == true AND $valid_email == true AND $valid_date == true)
				{
					
				if(is_uploaded_file($_FILES['image']['tmp_name']))
					{
					// once the form is correctly validated, upload any image selected to the webserver and set the 'image' field in the users table to the correspsonding location

						//set the location of the upload folder
						$upload_location = $path."assets/images/users/";

						// create the file name of uploaded file
						$filename = basename($_FILES['image']['name']);

						// create the string that will be inserted into the users table
						$image = "images/users/".$filename;

						// set location to store uploaded file
						$upload = $upload_location . $filename;

						// get the temporary file name
						$tempFile = $_FILES['image']['tmp_name'];

						// move file to its new home
						move_uploaded_file($tempFile, $upload);	
					}

					$query = "UPDATE user
							SET
							password = sha1('$passw'),
							email = '$email',
							title = '$selected_title',
							first_name = '$first_name',
							last_name = '$last_name',
							gender = '$selected_gender',
							dob = '$dob',
							image = '$image',
							is_admin = '$admin'
							WHERE username = '$usern'";
					
					mysqli_query($connection, $query);

					echo "<script type=\"text/javascript\">document.location.href=\"my_profile.php\";</script>";

				}	
		}		
	echo "<img class='main' src='".$path."assets/".$image."' />";
?>
	<form action="edit_my_profile.php" method="post" enctype="multipart/form-data">
		<p>
		<span class='xxs'>Title</span>
			<select name="title" id="title">
				<?php
					// pull the values for the drop down menu out of the corresponding array
					foreach ($title_array as $option => $value)
					{
						if($value == $title)
								{
									// if the form has been submitted, mark the previously selected option as the 'selected' option
									echo "<option selected>". $value ."</option>";
								}
		
						else
							{
								// if no option has been previously selcted, then pull the values as normal
								echo "<option>". $value ."</option>";
							}
					}
				?>
			</select>
		</p>

		<p>
		<span class="xxs">First name</span>
		<input name="first_name" id="first_name" value="<?php echo $first_name; ?>">

			<?php
				// if, once the form has been submitted, and the field is still empty, show an error
				if(isset($_POST['submit']))
					{
						if($not_empty_first_name == false)
							{
								echo $required;
							}
					}	
			?>	
		</p>
		
		<p>
		<span class="xxs">Last name</span>
		<input name="last_name" id="last_name" value="<?php echo $last_name; ?>">

			<?php
				// if, once the form has been submitted, and the field is still empty, show an error
				if(isset($_POST['submit']))
					{
						if($not_empty_last_name == false)
							{
								echo $required;
							}
					}	
			?>
		</p>

		<p>
		<span class='xxs'>Gender</span>
			<select name="gender" id="gender">				
				<?php
					// pull the values for the drop down menu out of the corresponding array
					foreach ($gender_array as $option => $value)
					{
						if($value == $gender)
								{
									// if the form has been submitted, mark the previously selected option as the 'selected' option
									echo "<option selected>". $value ."</option>";
								}
						

						else
							{
								// if no option has been previously selcted, then pull the values as normal
								echo "<option>". $value ."</option>";
							}
					}
				?>
			</select>	
		</p>

		<p>
		<span class='xxs'>Email address</span>
		<input name="email" id="email" value="<?php echo $email; ?>">

			<?php
				
				if(isset($_POST['submit']))
					{	
						if ($not_empty_email == false)
							{
								echo $required;
							}

						elseif($valid_email == false)
							{
								echo $invalid;
							}
					}
			?>
		</p>

		<p>
		<span class='xxs'>Date of birth</span>

			<select name="day" id="day">				
				<?php
					$day=1;
						
						for ($day; $day<=31; $day++)
						{
							if($day == $selected_day)
								{
									echo "<option selected>". $selected_day ."</option>";
								}
							else
								{
									echo "<option>". $day ."</option>";
								}
						}
				?>
			</select>
			
			<select name="month" id="month">

				<?php

					foreach ($month_array as $value => $detail)	
						{
							if($value == $selected_month)
								{
									echo "<option selected>". $value ."</option>";
								}
							else
								{
									echo "<option>". $value ."</option>";
								}	
						}
				?>	

			</select>
				
			<select name="year" id="year">
				<?php

					$age = 10;
					$year = date("o") - $age;
					$stop = 1920;

					for ($year; $year > $stop; $year--)
						{
						if($year == $selected_year)
								{
									echo "<option selected>". $year ."</option>";
								}
							else
								{
									echo "<option>". $year ."</option>";
								}
						}
				?>	
			</select>

			<?php
				if(isset($_POST['submit']))
					{
						if($valid_date == false)
							{
								echo $invalid;
							}
					}
			?>
		</p>

		<p>
			<span class='xxs'>Image</span>
			<input type="file" name="image" id="image">
		</p>

		<p>
			<span class='xxs'>&nbsp;</span><a href='edit_my_profile.php?delete=yes' name='delete_image' id='delete_image'>delete image</a>
		</p>		

		<?php
		if($admin == 0)
			{
				echo "
					<p>
					<span class='xxs'>Admin password</span>
					<input type='password' name='admin' id='admin'>
					</p>
					<p>
					<span class='xxs'>&nbsp;</span>Password = lecturer's first name
					</p>";
			}
		?>	

		<p>
		<span class='xxs'>Password</span>
		<input type="password" name="passw" id="passw" value="<?php echo $passw ?>">
		<?php
			// if, once the form has been submitted, the passwords don't match, then show an error
			if(isset($_POST['submit']))
				{
					if($not_empty_password == false)
						{
							echo $required;
						}	
				}	
			?>
		</p>

		<p>
		<span class='xxs'>Confirm</span>
		<input type="password" name="passw_check" id="passw_check" value="<?php echo $passw_check; ?>">
		<?php
			// if, once the form has been submitted, the passwords don't match, then show an error
			if(isset($_POST['submit']))
				{
					if($password_match == false)
						{
							echo $not_matching;
						}
				}	
			?>
		</p>

		<p>
		<span class='xxs'>&nbsp;</span>
		<input class="submit" type="submit" id="submit" name="submit" value="update">
		<a href="my_profile.php">Cancel</a>
		</p>
	</form>

<?php

	//login include
	include($path."assets/includes/login.php");

	//footer include
	include($path."assets/includes/footer.php");
?>