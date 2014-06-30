<?php

	$page = "login";
	require('settings.php');

	$error_text = "";
	
	if(isset($_POST['submit'])) 
	{
		
		$username = base64_encode($_POST['username']);
		$password = md5($_POST['password']);
		
		if($username === $admin_username && $password === $admin_password)
		{
			setcookie("spamLog_username", $username, time()+3600); // One hour.
			setcookie("spamLog_password", $password, time()+3600); // One hour.
			
			header("Location: index.php");
		}
		else
		{
			$error_text = "Incorrect username and/or password.";
		}		
	}
?>

<html>
<head>
	<title>Login - Log Spam Analyzer</title>
</head>
<body>
	<h1>Login:</h1>
	<span style="color:red;"><?php echo $error_text; ?></span>
	<form name="login" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
		Username: <input type="text" name="username"><br>
		Password: <input type="password" name="password"><br>
		<input type="submit" name="submit">
	</form>
</body>
</html>