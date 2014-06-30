<?php
	require('settings.php');
	
	$changes = "";

	if(isset($_POST['submit'])) 
	{ 
		
		$logPath = $_POST['logPath'];
		
		$settings = file_get_contents("settings.php");
		$settings = str_replace("\$path_to_log = \"$path_to_log\";", "\$path_to_log = \"$logPath\";", $settings);
		file_put_contents("settings.php", $settings);
		
		$changes = "Changes saved.";

		$path_to_log = $logPath;
	}
?>

<html>
<head>
	<title>Settings - Log Spam Analyzer</title>
</head>
<body>
	<a href="analyzer.php">Analyze Log</a><br />
	<a href="admin.php">Settings</a> 
	<?php
		if($path_to_log === "")
		{
			echo "<-- Your log path is not set.  Set it here.";
		}
	?>


	<span style="color:green;"><?php echo $changes; ?></span>
	<form name="settings" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
		Set the path to your Apache log file.  This should be a relative path looking something like "../../logs/yoursite.com/yoursite.log".<br>
		Log file path: <input type="text" name="logPath" value="<?php echo addslashes($path_to_log);?>"><br>
		<input type="submit" name="submit">
	</form>
</body>
</html>