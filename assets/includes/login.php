
<!-- end page content &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->

					</div>

				</article>

				<article>

					<div class="content">
						<a href="http://facebook.com/philip.henning"><img class="links" src="<?php echo $path; ?>assets/images/page_images/facebook.png" alt="Facebook" /></a>
						<a href="http://twitter.com/philipdhenning"><img class="links" src="<?php echo $path; ?>assets/images/page_images/twitter.png" alt="Twitter" /></a>
						<a href="http://validator.w3.org/"><img class="links" src="<?php echo $path; ?>assets/images/page_images/HTML5.png" alt="Valid HTML5" /></a>
						<a href="http://validator.w3.org/"><img class="links" src="<?php echo $path; ?>assets/images/page_images/CSS.png" alt="Valid HTML5" /></a>
						
					</div>

				</article>

			</section>
				
			<section id="right">

					<aside>

					<?php
							
						if(!isset($_SESSION['username']))
						{
							
					?>

					<div class="aside_layout">

						<h3>Members login:</h3>

						<form name="login" id="login" method="post" action="<?php echo $path; ?>pages/login/set_session.php">
							<label>Username:</label>
							<input type="text" name="username" id="username" />
							<label>Password:</label>
							<input type="password" name="password" id="password" />
							<input type="submit" value="login" name="submit" id="submit" />
						</form>

						<?php

							if(isset($_SESSION['message']))
							{
								echo "<p class='message'>". $_SESSION['message']."</p>";
							}

						?>

					</div>	

					<?php

						}

						else
						{		
					?>

					<div class="aside_layout">
						
						<?php
							echo "<h3>".$_SESSION['username']."</h3>
								<ul>
								<li><a href='".$path."pages/login/my_profile.php'>my profile</a></li>
								<li><a href='".$path."pages/login/edit_my_profile.php'>edit my profile</a></li>
								<li><a href='".$path."pages/login/my_comments.php'>edit my comments</a></li>
								</ul>";

							//store the sql query as a variable
							$query =  "SELECT is_admin FROM user WHERE username = '".$_SESSION['username']."'";

							// run the query
							$result = mysqli_query($connection, $query);                     

							//if $row is greater than 0, the username / password combination has been found, so login
						    if (mysqli_num_rows($result) > 0)
							{
								while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
								{
									if($row['is_admin'] == 1)
									{
										echo "<h5>Admin section</h5>
											<ul>
											<li><a href='".$path."pages/login/admin/statistics.php'>site statistics</a></li>
											<li><a href='".$path."pages/login/admin/add_product.php'>add product</a></li>
											<li><a href='".$path."pages/login/admin/manage_products.php'>manage products</a></li>
											<li><a href='".$path."pages/login/admin/manage_comments.php'>manage comments</a></li>
											<li><a href='".$path."pages/login/admin/manage_users.php'>manage users</a></li>
											</ul>";
									}
								}
							}
									
							echo "<ul><li><h5><a href='".$path."pages/login/logout.php'>Logout</a></h5></li></ul></div>";
						}			
						?>				
					</aside>

					<aside>

						<div class="aside_layout">

							<a class="twitter-timeline" href="https://twitter.com/PhilipDHenning?ref_src=twsrc%5Etfw">Tweets by PhilipDHenning</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>>

						</div>

					</aside>

				</section>
				
				<div class="clear"></div>
