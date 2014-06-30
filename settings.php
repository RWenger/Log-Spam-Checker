<?php
	$admin_username = "";
	$admin_password = "";
	
	// The path to the log file to check.
	$path_to_log = "";
	
	$loggedIn = false;
	
	if($admin_username === "")
	{
		header("Location: setAdmin.php");
	}
	else
	{		
		if (isset($_COOKIE["spamLog_username"]) && isset($_COOKIE["spamLog_password"]))
		{
			if(base64_decode($_COOKIE["spamLog_username"]) === base64_decode($admin_username) && $_COOKIE["spamLog_password"] === $admin_password)
			{
				$loggedIn = true;
			}
			else
			{
				$loggedIn = false;
			}
		}
		
		if(!$loggedIn && !($page === "login"))
		{
			header("Location: login.php");
		}
	}
?>