<?php
	$admin_username = "";
	$admin_password = "";
	
	if($admin_username === "")
	{
		header("Location: setAdmin.php");
	}
	
	// The path to the log file to check.
	$path_to_log = "";
?>