<?php
require_once ("./header.php");
logged_in_only ();
?>

<h1 id="caption">Import Bookmarks</h1>

<div style="min-width: <?php echo 230 + $settings['column_width_folder']; ?>px;">
	<div id="menu">
		<h2 class="nav">Bookmarks</h2>
		<ul class="nav">
		  <li><a href="./index.php">My Bookmarks</a></li>
		</ul>
	
		
	</div>

	<div id="main">
		<div id="content">

	<h2 class="text-center">DATA BASE Project</h2>
	<h4 class="text-center">Online Bookmarking Website</h4>
	<hr>
		
	<h6 class="text-center">Made by:</h6> <p class="text-center">Rahul Juneja (13BCE0404)</p>
		
	
	

		</div>
	</div>
</div>

<?php
print_footer ();
require_once (ABSOLUTE_PATH . "footer.php");
?>
