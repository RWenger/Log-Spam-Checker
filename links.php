<a href="index.php">Home</a><br>
<a href="analyzer.php">Analyze Log</a><br>
<a href="admin.php">Settings</a>
	
		<?php
		
		if($path_to_log === "")
		{
			echo "<-- Your log path is not set.  Set it here.";
		}
		?>

</br>
<a href="logout.php">Log Out</a><br>
<br>
<br>