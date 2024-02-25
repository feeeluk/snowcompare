<?php
	// set the root folder (site root not server root) in relation to this page
	$path = "../../";

	// every page can have a different title
	$page_title = "Product type";

	//connect to database_connection
	include($path."assets/includes/database_connection.php");
	
	// connect to site_details
	include($path."assets/includes/site_details.php");

	//header include
	include($path."assets/includes/header.php");

	// %%%%%%%%%%%%%%%%%%%%%%%%%% page content %%%%%%%%%%%%%%%%%%%%%%%%%%%

	// if the page has been passed a specific type variable then do the following
	if (isset($_GET['type']))
		{
			$type = $_GET['type'];

			// create sql query statements

			// first query that gets the description for each type of product
			$description_query = "SELECT description 
	         					FROM type
	         					WHERE type.name = '".$type."'
	         					LIMIT 1";

			// main query - all products from specific product type
			$query = "SELECT product.id AS product_id, product.name AS product_name, product.manufacturer_id AS manufacturer_id,year, price, product.description AS product_description, manufacturer, image, type.name AS type_name
					FROM product
					LEFT JOIN type ON product.type_id = type.id
					LEFT JOIN manufacturer ON product.manufacturer_id = manufacturer.id
					WHERE type.name = '".$type."'
					ORDER BY manufacturer";									

			// print the type of the product selected
			echo "<h2>".$type."</h2>";

         	// run the description query
         	$description_result = mysqli_query($connection, $description_query);
         	if(mysqli_num_rows($description_result) > 0)
        		{
		         	while($description_row = mysqli_fetch_array($description_result, MYSQLI_ASSOC))
						{
							echo "<p class='description'>".$description_row['description']."</p>";
						}
				}			

			// run the main query
			$result = mysqli_query($connection, $query);

			//if more than 0 results are returned, then
		    if (mysqli_num_rows($result) > 0)
		        {

	        	//result statistics
	        	$result_count = mysqli_num_rows($result);

	        	if(mysqli_num_rows($result) == 1)
	        		{
	        			$result_text = " product";
	        		}
	        	else
	        		{
	        			$result_text = " products";
	        		}

	         	echo "<h4>".$result_count." matching ".$result_text." found</h4>";



				while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
					{
						echo "<div class='results'>";

						// sub query that returns the first image for each product
						$image_query = "SELECT name, location
										FROM product, product_image
										WHERE product.id = product_image.product_id
										AND product.id = '".$row['product_id']."'
										LIMIT 1";						

						// run image_query
						$image_result = mysqli_query($connection, $image_query);

						if(mysqli_num_rows($image_result) > 0)
							{ 
								while($image_row = mysqli_fetch_array($image_result, MYSQLI_ASSOC))
									{
										echo "<a href='product.php?product_id=".$row['product_id']."&amp;manufacturer_id=".$row['manufacturer_id']."'><img src='".$path."/assets/".$image_row['location']."' alt='product' /></a>";
									}
							}

						echo "<a href='manufacturer.php?manufacturer=".$row['manufacturer']."'><img class='logo' src='".$path."/assets/".$row['image']."' alt='image' /></a>
							<h4>".$row['manufacturer']." - ".$row['product_name']."</h4>
							<span class='xs'>Name:</span><a href='product.php?product_id=".$row['product_id']."&amp;manufacturer_id=".$row['manufacturer_id']."'>".$row['product_name']."</a><br />
							<span class='xs'>Model:</span>".$row['product_id']."<br />
							<span class='xs'>Manufacturer:</span><a href='manufacturer.php?manufacturer=".$row['manufacturer']."'>".$row['manufacturer']."</a><br />
							<span class='xs'>Year:</span>".$row['year']."<br />
							<span class='xs'>Price:</span>&#163;".$row['price']."<br />
							<span class='xs'>Description:</span><span class='m'>".substr($row['product_description'],0,200)."... </span>
							</div>";
					}						
				}

		    else
		    	{
		    		echo "<h4>Sorry, there are currently no products in this section</h4>";
		    	}	
		}

	// if the page has NOT been passed a specific type variable then do the following
	else
		{
			$query = "SELECT * FROM type ORDER BY name";

			// store the result of the query as a variable (this will be an array)
			$result = mysqli_query($connection, $query);

			//if $row is greater than 0 show results
		    if (mysqli_num_rows($result) > 0)
		        {
					while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
						{			
							echo "<p><a href='type.php?type=".$row['name']."'>".$row['name']."</a></p>";
						}
		    	}		
		}

	// %%%%%%%%%%%%%%%%%%%%%%%%%% page content end %%%%%%%%%%%%%%%%%%%%%%%%%%%

	//login include
	include($path."assets/includes/login.php");

	//footer include
	include($path."assets/includes/footer.php");
?>

