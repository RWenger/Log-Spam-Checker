<?php

if(isset($_POST['submit'])) 
{
	
    $name = base64_encode($_POST['username']);
	$password = md5($_POST['password']);
	
	if(strlen($name) < 3 || strlen($_POST['password']) < 3)
	{
		echo "Your username and password must both be at least 3 characters long.";
	}
	else
	{
		$settings = file_get_contents("settings.php");
		$settings = str_replace("\$admin_username = \"\";", "\$admin_username = \"$name\";", $settings);
		$settings = str_replace("\$admin_password = \"\";", "\$admin_password = \"$password\";", $settings);
		file_put_contents("settings.php", $settings);
		
		header("Location: index.php");
	}
	
}

?>

<html>
<head>
	<title>Setup Admin Password - Log Spam Analyzer</title>
	<link rel="stylesheet" type="text/css" href="theme/style.css" />
</head>
<body>
	<div id="container">
	<h1>Set up your administrative account account:</h1>
	<form name="adminDetails" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
		Username: <input type="text" name="username"><br>
		Password: <input type="password" name="password"><br>
		<input type="submit" name="submit">
	</form>
	</div>
</body>
</html>