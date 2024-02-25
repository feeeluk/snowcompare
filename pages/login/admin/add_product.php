<?php
	// set the root folder (site root not server root) in relation to this page
	$path = "../../../";

	// every page can have a different title
	$page_title = "Add product";

	// connect to site_details
	include($path."assets/includes/site_details.php");

	//connect to db
	include($path."assets/includes/database_connection.php");

	// blank values for form fields when page first loads
	$product_id ="";
	$name ="";
	$manufacturer ="";
	$type ="";
	$price ="";
	$year ="yyyy";
	$description ="";
	$insert_query_1 ="";

	// variables to store validation. True for valid, false for invalid. These are intentionally set as false, so that the form must validate correctly before it will send. Each form input has a check to see if the corresponding variable is true or false (which is only tested AFTER the form has been submitted).
	$not_empty_product_id = false;
	$new_product_id = false;
	$not_empty_name = false;
	$not_empty_manufacturer = false;
	$not_empty_type = false;
	$not_empty_price = false;
	$not_empty_year = false;
	$not_empty_description = false;
	$valid_year = false;

	// variables that store error messages
	$required = "<span>Required field</span>";
	$not_unique = "<span>Product already exists</span>";
	$invalid = "<span>1902 or newer</span>";

	// if the submit button has been clicked 
	if(isset($_POST['submit']))
	{
		// assign form input values to the corresponding variables.
		// Variables are used to store the currently selected option of drop down fields, so that the selected option will be maintained when the form is refreshed
		$product_id = mysqli_real_escape_string($connection, strip_tags(strtoupper(trim($_POST['product_id']))));
		$name = mysqli_real_escape_string($connection, strip_tags(ucfirst(trim($_POST['name']))));
		$selected_manufacturer = trim($_POST['manufacturer']);
		$selected_type = mysqli_real_escape_string($connection, strip_tags(trim($_POST['type'])));
		$price = mysqli_real_escape_string($connection, strip_tags(trim($_POST['price'])));
		$year = mysqli_real_escape_string($connection, strip_tags(trim($_POST['year'])));
		$description = mysqli_real_escape_string($connection, strip_tags(ucfirst(trim($_POST['description']))));

		// check if the product_id field contains a value
		if($product_id !="")
		{
			$not_empty_product_id = true;
		}

		// if the product_id already exists in the database
		// create query that to find any matching product_id fields. 
		$query = "SELECT id FROM product WHERE id = '$product_id'";
		$result = mysqli_query($connection, $query) or die(mysqli_error($connection));

		// if any result is returned then a record already exists in the database and cannot be used
		if (mysqli_num_rows($result) < 1)
		{
			$new_product_id = true;
		}


		// check if the name field contains a value
		if($name !="")
		{
			$not_empty_name = true;
		}

		// check if the manufacturer field contains a value
		if($selected_manufacturer !="Manufacturer")
		{
			$not_empty_manufacturer = true;
		}

		// check if the type field contains a value
		if($selected_type !="Type")
		{
			$not_empty_type = true;
		}

		// check if the price field contains a value
		if($price !="")
		{
			$not_empty_price = true;
		}

		// check if the year field contains a value
		if($year !="")
		{
			$not_empty_year = true;
		}

		// check if the year field is greater than or equal to 1902 value
		if($year >="1902")
		{
			$valid_year = true;
		}		

		// check if the description field contains a value
		if($description !="")
		{
			$not_empty_description = true;
		}
			
		// form submit validation	
		if ($not_empty_product_id == true AND $new_product_id == true AND $not_empty_name == true AND $not_empty_manufacturer == true AND $not_empty_type == true AND $not_empty_price == true AND $not_empty_year == true AND $valid_year == true AND $not_empty_description == true)
		{
			// form submitted successfully

			// insert update query
			$query = "INSERT INTO product (id, name, manufacturer_id, type_id, price, year, description)
					SELECT '$product_id' AS product_id, '$name' AS name, manufacturer.id AS manufacturer_id, type.id AS type_id, '$price' AS price, '$year' AS year, '$description' AS description
					FROM manufacturer, type
					WHERE manufacturer.manufacturer = '$selected_manufacturer' AND type.name = '$selected_type'";
				
			mysqli_query($connection, $query) or die(mysqli_error($connection));


			// UPLOAD PRODUCT IMAGES TO SERVER
					
			// create upload image function				
			function upload_image($x)
			{
				// import all relevant variables into the function
				global $product_id;
				global $connection;
				global $path;

				// if any pictures have been selected
				if(is_uploaded_file($_FILES[$x]['tmp_name']))
				{
					//set the location of the upload folder
					$upload_location = $path."assets/images/products/";

					// create the file name of uploaded file
					$filename = basename($_FILES[$x]['name']);

					// set location to store uploaded file
					$upload = $upload_location . $filename;	

					// create the string that will be inserted into the users table
					$image_location = "images/products/".$filename;

					// get the temporary file name
					$temp = $_FILES[$x]['tmp_name'];

					// move file to its new home
					move_uploaded_file($temp, $upload);
				}

				// otherwise, if no pictures have been selected
				else
				{
					$image_location = "images/products/placeholder.jpg";
				}

				// create the insert query
				$insert_query = "INSERT INTO product_image (product_id, location)
							VALUES ('$product_id', '$image_location')";

				// run the query
				mysqli_query($connection, $insert_query);
			}

			// call upload image function, pass the name of the image input and number of
			upload_image("image_1");

			upload_image("image_2");

			upload_image("image_3");																

			echo "<script type=\"text/javascript\">document.location.href=\"manage_products.php\";</script>";
		}

		else
		{
			// not submitted
		}

	}

	//header include
	include($path."assets/includes/header.php");

	// if session is set
	if(isset($_SESSION['username']))
	{
		$username = $_SESSION['username'];

		// check to see if the user is an admin
		$query =  "SELECT is_admin FROM user WHERE username = '$username'";

		// run the query
		$result = mysqli_query($connection, $query) or die(mysqli_error($connection));; 

		if (mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				if($row['is_admin'] == 1)
				{
	?>
					<form id="add" action="add_product.php" method="post" enctype="multipart/form-data">

					<p>
						<span class="xxs">Product code</span>
						<input type="text" name="product_id" id="product_id" value="<?php echo $product_id; ?>">

						<?php
							// if, once the form has been submitted, and the field is still empty, show an error
							if(isset($_POST['submit']))
								{
									if($not_empty_product_id == false)
										{
											echo "<span class='error'>".$required."</span>";
										}

									if($new_product_id == false)
										{
											echo "<span class='error'>".$not_unique."</span>";
										}	
								}	
						?>	
					</p>

					<p>
						<span class="xxs">Name</span>
						<input type="text" name="name" id="name" value="<?php echo $name ?>">
						<?php
							// if, once the form has been submitted, the passwords don't match, then show an error
							if(isset($_POST['submit']))
								{
									if($not_empty_name == false)
										{
											echo "<span class='error'>".$required."</span>";
										}	
								}	
						?>
					</p>	

					<p>
						<span class="xxs">Manufacturer</span>
						<select name="manufacturer" id="manufacturer">
						<?php
							// pull the values for the drop down menu out of the corresponding database table
							$query = "SELECT id, manufacturer FROM manufacturer";
							$result = mysqli_query($connection, $query) or die(mysqli_error($connection));;
							if(mysqli_num_rows($result) > 0)
							{
								// Set the default drop down option unless already selected
								echo "<option selected>Manufacturer</option>";

								while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
								{	
									if($row['manufacturer'] == $selected_manufacturer)
									{
										// if the form has been submitted, mark the previously selected option as the 'selected' option
										echo "<option selected>".$row['manufacturer']."</option>";
									}

									else
									{
										// if no option has been previously selcted, then pull the values as normal
										echo "<option>".$row['manufacturer']."</option>";
									}
								}
							}
						?>

						</select>

						<?php
							// if, once the form has been submitted, and the field has not been selected, show an error
							if(isset($_POST['submit']))
							{
								if($not_empty_manufacturer == false)
								{
									echo "<span class='error'>".$required."</span>";
								}
							}	
						?>
					</p>

					<p>
						<span class="xxs">Type</span>
						<select name="type" id="type">
							<?php
								// pull the values for the drop down menu out of the corresponding database table
								$query = "SELECT id, name FROM type";
								$result = mysqli_query($connection, $query) or die(mysqli_error($connection));;
								if(mysqli_num_rows($result) > 0)
								{
									// Set the default drop down option unless already selected
									echo "<option selected>Type</option>";

									while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
									{
										if($row['name'] == $selected_type)
												{
													// if the form has been submitted, mark the previously selected option as the 'selected' option
													echo "<option selected>".$row['name']."</option>";
												}
						
										else
											{
												// if no option has been previously selcted, then pull the values as normal
												echo "<option>".$row['name']."</option>";
											}
									}
								}
							?>
						</select>

						<?php
							// if, once the form has been submitted, and the field has not been selected, show an error
							if(isset($_POST['submit']))
							{
								if($not_empty_type == false)
									{
										echo "<span class='error'>".$required."</span>";
									}
							}	
						?>
					</p>

					<p>
						<span class="xxs">Price</span>
						<input type="text" name="price" id="price" value="<?php echo $price; ?>">

						<?php
						// if, once the form has been submitted, and the field is still empty, show an error
						if(isset($_POST['submit']))
						{
							if($not_empty_price == false)
							{
								echo "<span class='error'>".$required."</span>";
							}
						}	
						?>	
					</p>
					
					<p>
						<span class="xxs">Year</span>
						<input type="text" name="year" id="year" value="<?php echo $year; ?>">

						<?php
						// if, once the form has been submitted, and the field is still empty, show an error
						if(isset($_POST['submit']))
						{
							if($not_empty_year == false)
							{
								echo "<span class='error'>".$required."</span>";
							}

							if($valid_year == false)
							{
								echo "<span class='error'>".$invalid."</span>";
							}							
						}	
						?>
					</p>

					<p>
						<span class="xxs">Image 1</span>
						<input type="file" name="image_1" id="image_1">
					</p>

					<p>
						<span class="xxs">Image 2</span>
						<input type="file" name="image_2" id="image_2">
					</p>

					<p>
						<span class="xxs">Image 3</span>
						<input type="file" name="image_3" id="image_3">
					</p>			

					<p>
						<span class="xxs top">Description</span>
						<textarea name="description" id="description" rows="20" cols="50" ><?php echo $description; ?></textarea> 
						<?php	
						if(isset($_POST['submit']))
						{	
							if ($not_empty_description == false)
							{
								echo "<span class='error top'>".$required."</span>";
							}
						}
						?>
					</p>

					<p>
							<span class="xxs">&nbsp;</span>
							<input class="submit" type="submit" id="submit" name="submit">
							<a href="../my_profile.php">Cancel</a>
					</p>

					</form>				

<?php
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