<?php
	//set the root folder location as a variable (site root not server root)
	$path = "./";

	// every page can have a different title
	$page_title = "Home";

	// connect to database_connection
	include($path."assets/includes/database_connection.php");

	// connect to site_details
	include($path."assets/includes/site_details.php");

	//header include
	include($path."assets/includes/header.php");						
?>
	
<div id="slider_container">
	<div id="slider_top">
		<img class="slider" src="<?php echo $path; ?>/assets/images/slider_images/blank.jpg" id="slide" alt="slider-images"/>
		<p id="slider_imageNumber"></p>
	</div>

	<div id="slider_bottom" class="slider_nav">
		<a href="#non"><img class="slider" src="<?php echo $path; ?>assets/images/slider_navigation/back.png" alt="back" onclick="back()" /></a>
		<a href="#non"><img class="slider" src="<?php echo $path; ?>assets/images/slider_navigation/pause.png" alt="pause" onclick="stop()"></a>
		<a href="#non"><img class="slider" src="<?php echo $path; ?>assets/images/slider_navigation/play.png" alt="play" onclick="start()"></a>
		<a href="#non"><img class="slider" src="<?php echo $path; ?>assets/images/slider_navigation/forward.png" alt="forward" onclick="forward()"></a>
	</div>
</div>



<h2>Welcome</h2>

<p>
Welcome to Snow Compare!<br />
Yes.. I really went there, ha-ha! Fortunately, though, there is no chance of seeing Gio Compario anywhere on this site.
</p>

<p>
The theme is based on reviewing products related to winter activities. From snowboards, skis, boots, bindings, etc to googles, helmets, gloves and much, much more!</p>

<p>
There are many products, and there are many references to, and quotes from comedy classics from the 80's and 90's.. mainly Blackadder, Fawlty Towers, Bottom, Red Dwarf, The Young Ones etc.
</p>

<p>
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse in pretium arcu. Nulla euismod placerat urna, et auctor odio congue eu. Morbi et condimentum ipsum, eget lacinia tortor. Nam consectetur dapibus felis, a pretium leo aliquam sit amet. Integer convallis nunc eu risus dictum imperdiet. Curabitur dignissim bibendum libero vitae ornare. Aliquam erat volutpat. Phasellus vel enim eros. Aenean luctus ante sapien, vitae mattis sapien mattis ac. Aenean quis eros varius erat adipiscing suscipit. Sed tincidunt at ligula nec dapibus. Proin ac nisi tortor. Praesent ornare blandit sem.
</p>

<p>I hope you enjoy..<br />
best wishes,<br />
Phil (creator)
</p>

<?php
	//login include
	include($path."assets/includes/login.php");

	//footer include
	include($path."assets/includes/footer.php");
?>
