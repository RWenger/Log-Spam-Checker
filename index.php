<?php
	require('settings.php');
?>

<html>
<head>
	<title>Log Spam Analyzer</title>
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
</body>
</html>