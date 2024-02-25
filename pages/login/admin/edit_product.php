<?php
	// set the root folder (site root not server root) in relation to this page
	$path = "../../../";

	// every page can have a different title
		if(isset($_GET['product']))
		{
			$page_title = "Edit product: ".$_GET['product'];
			$product_id = $_GET['product'];
		}

		else
		{
			$page_title = "Edit product: ".$_POST['product'];
			$product_id = $_POST['product'];
		}

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
		$result = mysqli_query($connection, $query) or die(mysqli_error($connection)); 

		if (mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				if($row['is_admin'] == 1)
				{
					//authenticated admin page start

					// variables to store validation. True for valid, false for invalid. These are intentionally set as false, so that the form must validate correctly before it will send. Each form input has a check to see if the corresponding variable is true or false (which is only tested AFTER the form has been submitted).
					$not_empty_name = false;
					$not_empty_manufacturer = false;
					$not_empty_type = false;
					$not_empty_price = false;
					$not_empty_year = false;
					$not_empty_description = false;

					// variables that store error messages
					$required = "<span class='error'>Required</span>";					

					$product_query = "SELECT product.name AS product_name, manufacturer.id AS manufacturer_id, manufacturer.manufacturer AS manufacturer, type.id AS type_id, type.name AS type_name, price, year, product.description AS description
									FROM product, manufacturer, type
									WHERE product.id = '$product_id'
									AND product.manufacturer_id = manufacturer.id
									AND product.type_id = type.id";

					$product_result = mysqli_query($connection, $product_query);

					if(mysqli_num_rows($product_result) > 0)
					{
						while($product_row = mysqli_fetch_array($product_result, MYSQLI_ASSOC))
						{
							// get values for form fields when page first loads
							$name =$product_row['product_name'];
							$selected_manufacturer =$product_row['manufacturer'];
							$selected_type =$product_row['type_name'];
							$price =$product_row['price'];
							$year =$product_row['year'];
							$description =$product_row['description'];
						}
					}

					// there was an SQL error
					else
					{
					    echo "SQL Error: " . $connection->error;
					}

					
					// if the delete image link was clicked
					if(isset($_GET['delete']))
						{
							$image_id = $_GET['image_id'];

							// delete image query
							$delete_query = "DELETE FROM product_image
											WHERE  id = '".$image_id."'";
							
							mysqli_query($connection, $delete_query) or die(mysqli_error($connection));
							echo "<script>window.location.assign('edit_product.php?product=".$product_id."');</script>";
						}

					// if the submit button has been clicked 
					if(isset($_POST['submit']))
					{
						// assign form input values to the corresponding variables. 'selected' variables are used to store the currently selected option of drop down fields, so that the selected option will be maintained when the form is refreshed
						$name = ucfirst(trim($_POST['name']));
						$selected_manufacturer = trim($_POST['manufacturer']);
						$selected_type = trim($_POST['type']);
						$price = trim($_POST['price']);
						$year = trim($_POST['year']);
						$description = ucfirst(trim($_POST['description']));

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

						// check if the description field contains a value
						if($description !="")
						{
							$not_empty_description = true;
						}

						// form submit validation	
						if ($not_empty_name == true AND $not_empty_manufacturer == true AND $not_empty_type == true AND $not_empty_price == true AND $not_empty_year == true AND $not_empty_description == true)
						{
							// form submitted successfully

							// insert update query
							$query = 
									"UPDATE product AS product
									SET
									name = '$name',
									product.manufacturer_id = (
									   SELECT manufacturer.id
									   FROM manufacturer AS manufacturer
									   WHERE manufacturer.manufacturer = '$selected_manufacturer'
									),
									product.type_id = (
									   SELECT type.id
									   FROM type AS type
									   WHERE type.name = '$selected_type'
									),
									price = '$price',
									year = '$year',
									description = '$description'
									WHERE id = '$product_id'";
							
							mysqli_query($connection, $query) or die(mysqli_error($connection));								

							// UPLOAD PRODUCT IMAGES TO SERVER
								
								// create upload image function				
								function upload_image($x)
								{
									// import all relevant variables into the function
									global $product_id;
									global $connection;

									// if any pictures have been selected
									if(is_uploaded_file($_FILES[$x]['tmp_name']))
										{
										// once the form is correctly validated, upload any image selected to the webserver and set the 'image' field in the users table to the correspsonding location

											//set the location of the upload folder
											$upload_location = "../../../assets/images/products/";

											// create the file name of uploaded file
											$filename = basename($_FILES[$x]['name']);

											// create the string that will be inserted into the users table
											$image_location = "images/products/".$filename;

											// set location to store uploaded file
											$upload = $upload_location . $filename;

											// get the temporary file name
											$temp_file = $_FILES[$x]['tmp_name'];

											// move file to its new home
											move_uploaded_file($temp_file, $upload);											

										// create the insert query
										$insert_query = "INSERT INTO product_image (product_id, location)
														VALUES ('$product_id', '$image_location')";

										// run the query
										mysqli_query($connection, $insert_query);
									}
								
								}

								// call upload image function, pass the name of the image input and number of
								upload_image("image1");							
								upload_image("image2");							
								upload_image("image3");							

							echo "<script type=\"text/javascript\">document.location.href=\"manage_products.php\";</script>";
						}
						
					}

					// SUB QUERY IMAGES %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
					// sub query that gets the images for the product
					$image_query = "SELECT name, location, product_image.id AS image_id
								FROM product, product_image
								WHERE product.id = product_image.product_id AND product.id = '".$product_id."'";

					// store the result of the query - only get one value from the array to use as the page image
					$image_result = mysqli_query($connection, $image_query." LIMIT 1") or die(mysqli_error($connection));

					//if $row is greater than 0 show results
				    if (mysqli_num_rows($image_result) > 0)
				        {		        	
							while($image_row = mysqli_fetch_array($image_result, MYSQLI_ASSOC))
								{
									echo "<img name='main' class='main' src='".$path."assets/".$image_row['location']."' />";
								}
						}

					else
						{
							echo "<p><b>No images found</b></p>";
						}
?>

					<form action="edit_product.php" method="post" enctype="multipart/form-data">

					<input type="hidden" name="product" id="product" value="<?php echo $product_id; ?>" />

						<p>
						<span class="xxs">Name</span>
						<input type="text" name="name" id="name" value="<?php echo $name ?>">
									<?php
								// if, once the form has been submitted, the passwords don't match, then show an error
								if(isset($_POST['submit']))
									{
										if($not_empty_name == false)
											{
												echo $required;
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
									$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
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
												echo $required;
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
									$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
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
												echo $required;
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
												echo $required;
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
												echo $required;
											}
									}	
							?>
						</p>

						<p>
						<h4>Images</h4>
						<?php
		
							// store the result of the query - only get one value from the array to use as the page image
							$image_result = mysqli_query($connection, $image_query) or die(mysqli_error($connection));

							//if $row is greater than 0 show results
						    if (mysqli_num_rows($image_result) > 0)
						        {		        	
									while($image_row = mysqli_fetch_array($image_result, MYSQLI_ASSOC))
										{
											echo "<p>
												<span class='xxs'>&nbsp;</span>".$image_row['location'].
												"<a href='edit_product.php?product=".$product_id."&delete=yes&image_id=".$image_row['image_id']."' name='delete_image' id='delete_image'> delete</a>
												</p>";
										}
						}

						?>
						</p>

						<p>
						<span class="xxs">Image 1</span>
						<input type="file" name="image1">
						</p>

						<p>
						<span class="xxs">Image 2</span>
						<input type="file" name="image2">
						</p>

						<p>
						<span class="xxs">Image 3</span>
						<input type="file" name="image3">
						</p>			

						<p>
						<span class="xxs top">Description</span>
						<textarea name="description" id="description" rows="20" cols="30" ><?php echo $description; ?></textarea> 

							<?php
								
								if(isset($_POST['submit']))
									{	
										if ($not_empty_description == false)
											{
												echo $required;
											}
									}
							?>
						</p>

						<p>
							<span class="xxs">&nbsp;</span>
							<input class="submit" type="submit" id="submit" name="submit">
							<a href="manage_products.php">Cancel</a>
						</p>

					</form>

				<?php

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