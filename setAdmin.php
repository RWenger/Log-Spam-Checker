<?php

if(isset($_POST['submit'])) 
{ 
	
    $name = base64_encode($_POST['username']);
	$password = md5($_POST['password']);
	
	if(strlen($name) < 3 || strlen($_POST['password'] < 3)
	{
		echo "Your username and password must both be at least 3 characters long.";
	}
	else
	{
		$settings = file_get_contents("settings.php");
		$settings = str_replace("\$admin_username = \"blank\";", "\$admin_username = \"$name\";", $data);
		$settings = str_replace("\$admin_password = \"blank\";", "\$admin_password = \"$password\";", $data);
		file_put_contents("settings.php", $settings);
		
		// Deletes this file.
		//
		// When editing, either:
		// 	1. Comment out this line.
		// 	2. Keep a backup handy.
		unlink("setAdmin.php");
		
		header("Location: index.php");
	}
	
}

?>

<html>
<head>
	<title>Log - Log Spam Analyzer</title>
</head>
<body>
	<form name="adminDetails" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
		Username: <input type="text" name="username"><br>
		Password: <input type="password" name="password"><br>
		<input type="submit">
	</form>
</body>
</html>