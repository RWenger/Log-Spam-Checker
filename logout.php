<?php
	setcookie("spamLog_username", "", time()-3600);
	setcookie("spamLog_password", "", time()-3600);
	
	header("Location: login.php");
?>