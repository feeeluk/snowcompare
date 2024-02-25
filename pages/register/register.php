<?php
	// set the root folder (site root not server root) in relation to this page
	$path = "../../";

	// every page can have a different title
	$page_title = "Register";

	//connect to database_connection
	include($path."assets/includes/database_connection.php");
	
	// connect to site_details
	include($path."assets/includes/site_details.php");
	
	// initialise variables. These are used to populate the form fields
	$usern ="";
	$passw ="";
	$retype_password ="";
	$title ="";
	$title_array = array("Title", "Mr", "Mrs", "Miss", "Mz");
	$first_name ="";
	$last_name ="";
	$gender ="";
	$gender_array = array("Gender", "Male", "Female");
	$email ="";
	$day ="";
	$month ="";
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
	$year ="";
	$admin ="";
	$admin_password = "pete";

	// variables to store validation. True for valid, false for invalid. These are intentionally set as false, so that the form must validate correctly before it will send. Each form input has a check to see if the corresponding variable is true or false (which is only tested AFTER the form has been submitted).
	$not_empty_username = false;
	$new_username = false;
	$not_empty_password = false;
	$password_match =false;
	$not_empty_title = false;
	$not_empty_first_name = false;
	$not_empty_last_name = false;
	$not_empty_email = false;
	$valid_email = false;
	$not_empty_gender = false;
	$valid_date =false;
	$valid_admin =0;
	
	// variables that store error messages
	$required = "<span class='error'>Required</span>";
	$invalid = "<span class='error'>Invalid</span>";
	$not_unique = "<span class='error'>Already exists</span>";
	$not_matching = "<span class='error'>Don't match</span>";

	// if the submit button has been clicked 
	if(isset($_POST['submit']))
		{
		// assign form input values to the corresponding variables. 'selected' variables are used to store the currently selected option of drop down fields, so that the selected option will be maintained when the form is refreshed
			$usern = mysqli_real_escape_string($connection, strip_tags(ucfirst(trim($_POST['usern']))));
			$passw = mysqli_real_escape_string($connection, strip_tags(trim($_POST['passw'])));
			$retype_password = mysqli_real_escape_string($connection, strip_tags(trim($_POST['retype_password'])));
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
			$admin = mysqli_real_escape_string($connection, strip_tags($_POST['admin']));
			

		// 1. if the username address field is not empty then set the empty_email variable as true
			if($usern !="")
				{
					$not_empty_username = true;
				}

		// 2. if the username does not already exist in the database then set the variable new_username as true

			// create query that finds any users with the same name as $usern. A HUGE issue I experienced here was the I could not use $username or $password as mysql / php or both, pulled the root username and password instead of the variable defined in the php.
			$query = "SELECT username FROM user WHERE username = '$usern'";
			$result = mysqli_query($connection, $query);

			// if any result is returned then a record already exists in the database and cannot be used
			if (mysqli_num_rows($result) > 0)
				{
					$new_username = false;
				}

			// if there aren't any records in the database then the suggested username is fine
			else
				{
					$new_username = true;
				}


		// 3. if the password address field is not empty then set the empty_email as true
			if($passw !="")
				{
					$not_empty_password = true;
				}

		// 4. if the passwords match set the valid_password variable to true
			if($passw == $retype_password)
				{
					$password_match = true;
				}

		// 5. if the title field is not the default value then set the empty_email as true
			if($selected_title !="Title")
				{
					$not_empty_title = true;
				}

		// 6. if the first_name address field is not empty then set the empty_email as true
			if($first_name !="")
				{
					$not_empty_first_name = true;
				}

		// 7. if the last_name address field is not empty then set the empty_email as true
			if($last_name !="")
				{
					$not_empty_last_name = true;
				}

		// 8. if the gender field is not the default value then set the empty_email as true
			if($selected_gender !="Gender")
				{
					$not_empty_gender = true;
				}

		// 9. if the email address field is not empty then set the empty_email as true
			if($email !="")
				{
					$not_empty_email = true;
				}

		// 10. if the email address format is correct then set the valid_email variable to true
			if(filter_var($email, FILTER_VALIDATE_EMAIL))
				{
					$valid_email = true;
				}
			
			// parse the results of the form so that they are in a date format recognised by the checkdate() function. The date_parse_from_fromat() function stores results in array (in this case we have saved the array as a variable)
				$date_array = date_parse_from_format("j.F.Y", $selected_day.".".$selected_month.".".$selected_year);				
			
			// build the checkdate() argument by refering to the associative array data contained within $date_array
				$result = checkdate($date_array["month"], $date_array["day"], $date_array["year"]);

		// 11. if the date is valid then set the valid_date variable to true - checkdate() returns a value of 1 if the date is valid
			if($result == 1)
				{
					$valid_date = true;
				}

		// if the admin_password is entered, then set valid_admin as true - not part of the validation
			if($admin == $admin_password)
				{
					$valid_admin = 1;
				}		

		// form submit validation
			// if all of the required input fields contain valid content then the form is complete 	
			if ($not_empty_username == true AND $not_empty_password == true AND $password_match == true AND $not_empty_title == true AND $not_empty_first_name == true AND $not_empty_last_name == true AND $not_empty_gender == true AND $not_empty_email == true AND $valid_email == true AND $valid_date == true AND $new_username == true)
				{
					
				if(!is_uploaded_file($_FILES['image']['tmp_name']))
					{
						$image_string = "images/users/placeholder.png";
					}
					
				else
					{
					// once the form is correctly validated, upload any image selected to the webserver and set the 'image' field in the users table to the correspsonding location

						//set the location of the upload folder
						$upload_location = $path."/assets/images/users/";

						// create the file name of uploaded file
						$filename = basename($_FILES['image']['name']);
						// create the string that will be inserted into the users table
						$image_string = "images/users/".$filename;

						// set location to store uploaded file
						$upload = $upload_location . $filename;

						// get the temporary file name
						$tempFile = $_FILES['image']['tmp_name'];

						// move file to its new home
						move_uploaded_file($tempFile, $upload);	
					}

					// creat insert query
					$query = "INSERT INTO user (username, password, email, title, first_name, last_name, gender, dob, image, joined, is_admin) VALUES ('$usern', sha1('$passw'), '$email', '$selected_title', '$first_name', '$last_name', '$selected_gender', '$dob', '$image_string', '$today', $valid_admin ) ";
					
					mysqli_query($connection, $query);

					$_SESSION['username'] = $usern;

					echo "<script type=\"text/javascript\">document.location.href=\"".$path."pages/login/my_profile.php\";</script>";

				}	
		}

	//header include
	include($path."assets/includes/header.php");
?>

<p>
	<form id="resgister" action="register.php" method="post" enctype="multipart/form-data">

		<p>
		<span class="xs">Username</span>
		<input name="usern" id="usern" value="<?php echo $usern; ?>">

			<?php
				// if, once the form has been submitted, and the field is still empty, show an error
				if(isset($_POST['submit']))
					{
						if($not_empty_username == false)
							{
								echo $required;
							}

						if($new_username == false)
							{
								echo $not_unique;
							}	
					}	
			?>	
		</p>

		<p>
		<span class="xs">Password</span>
		<input type="password" name="passw" id="passw" value="<?php echo $passw ?>">
					<?php
				// if, once the form has been submitted, the passwords don't match, then show an error
				if(isset($_POST['submit']))
					{
						if($password_match == false)
							{
								echo $not_matching;
							}

						if($not_empty_password == false)
							{
								echo $required;
							}	
					}	
			?>
		</p>	

		<p>
		<span class="xs">Retype password</span>
		<input type="password" name="retype_password" id="retype_password" value="<?php echo $retype_password; ?>">
		</p>

		<p>
		<span class="xs">Title</span>
			<select name="title" id="title">
				<?php
					// pull the values for the drop down menu out of the corresponding array
					foreach ($title_array as $option => $value)
					{
						if($value == $selected_title)
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


			<?php
				// if, once the form has been submitted, and the field has not been selected, show an error
				if(isset($_POST['submit']))
					{
						if($not_empty_title == false)
							{
								echo $required;
							}
					}	
			?>
		</p>

		<p>
		<span class="xs">First name</span>
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
		<span class="xs">Last name</span>
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
		<span class="xs">Gender</span>
			<select name="gender" id="gender">				
				<?php
					// pull the values for the drop down menu out of the corresponding array
					foreach ($gender_array as $option => $value)
					{
						if($value == $selected_gender)
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

			<?php
				// if, once the form has been submitted, and the field has not been selected, show an error
				if(isset($_POST['submit']))
					{
						if($not_empty_gender == false)
							{
								echo $required;
							}
					}	
			?>	
		</p>

		<p>
		<span class="xs">Email address</span>
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
		<span class="xs">Date of birth</span>

			<select name="day" id="day">				
				<?php
					$day=1;
						
						for ($day; $day<=31; $day++)
						{
							if($day == $selected_day)
								{
									echo "<option selected>". $day ."</option>";
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
			<span class="xs">Image</span>
			<input type="file" name="image" id="image">
			</p>

			<p>
			<span class="xs">Admin password</span>
			<input type="password" name="admin" id="admin" value="<?php echo $admin; ?>"> Password = lecturer's first name
			</p>

			<p>
			<span class="xs">&nbsp;</span>
			<input class="submit" type="submit" id="submit" name="submit">
			<a href="<?php echo $path; ?>">Cancel</a>
			</p>
	</form>

</p>

<?php
	//login include
	include($path."assets/includes/login.php");

	//footer include
	include($path."assets/includes/footer.php");
?>