<html>
<head>
	<title>Log Spam Analyzer</title>
	<link rel="stylesheet" type="text/css" href="tableSorter/themes/blue/style.css">

	<script type="text/javascript" src="jquery-1.11.0.min.js"></script> 
	<script type="text/javascript" src="tableSorter/jquery.tablesorter.js"></script>
	<script type="text/javascript">
	$(document).ready(function() 
		{ 
			$("#ipTable").tablesorter({sortList: [[1,1]]}); 
			$("#userAgentTable").tablesorter({sortList: [[1,1]]}); 
		} 
	); 
	</script>
	
	<style type="text/css">
	
	    body
		{
			text-align:center;
		}
	
		table.sortable
		{
			border:2px solid #DDDDDD;
			margin-left:auto;
			margin-right:auto;
		}
		
		table.sortable tr:nth-child(even)
		{
			background: #DDDDDD;
		}
		
		table.sortable thead
		{
			background-color: #DDDDDD;
		}
		
		table.sortable th.header.headerSortUp
		{ 
			background-image: url(tableSorter/themes/blue/asc.gif); 
			background-color: #AAAAAA; 
		}
		
		table.sortable th.header.headerSortDown
		{ 
			background-image: url(tableSorter/themes/blue/desc.gif); 
			background-color: #AAAAAA; 
		}
		
		table.sortable th.header
		{ 
			background-image: url(tableSorter/themes/blue/bg.gif);     
			cursor: pointer; 
			font-weight: bold; 
			background-repeat: no-repeat; 
			background-position: center left; 
			padding-left: 20px; 
			border-right: 1px solid #dad9c7; 
			margin-left: -1px; 
		}
	</style>
	
</head>
<body>

	<?php
	
	$numberOfBlocklistRequests = 0;
	require 'blacklistManager.php';

	$file = fopen("log.txt", "r");
	if($file)
	{
		$i = 1;
		$ipList = array();
		$userAgentList = array();

		while(($record = fgets($file)) != false)
		{
			/* Old analysis code.
			
			$splitOne = explode(" - - ", $record);
			$splitTwo = explode("] ", $splitOne[1]);
			$splitThree = explode("\"", $splitTwo[1], 3);
			$splitFour = explode(" ", $splitThree[2], 3);
			$splitFive = explode("\"", $splitFour[2]);
			
			
			$ip = $splitOne[0];
			$statusCode = $splitFour[1];
			$responseSize = $splitFive[0];
			$referrer = $splitFive[1];
			$userAgent = $splitFive[3];
			
			*/
			
			 
			
			//More reliable IP and UA results.
			
			$splitTest = explode(" ", $record);
			$splitTest2 = explode("\"", $record);
			
			$ip = $splitTest[0];
			$userAgent = $splitTest2[5];
			
			
			
		/*
			echo "<br><br>";
			echo "---------- Record " . $i . " -----------<br>";
			echo "IP: " . $ip . "<br>";
			echo "Status Code: " . $statusCode . "<br>";
			echo "Response Size: " . $responseSize . "<br>";
			echo "Referrer: " . $referrer . "<br>";
			echo "User Agent: " . $userAgent . "<br>";
			echo "<br><br>";
			echo "-----------------------------";*/
			
			$j = 0;
			$inArray = 0; 
			foreach($ipList as $entry)
			{
				if($entry[0] === $ip)
				{
					$ipList[$j][1] = $ipList[$j][1] + 1;
					$inArray = 1;
					break;
				}
				$j++;
			}
			if($inArray == 0)
			{
				$ipList[] = array($ip, 1);
			}
			
			$k = 0;
			$inArray = 0; 
			foreach($userAgentList as $uaEntry)
			{
				if($uaEntry[0] === $userAgent)
				{
					$userAgentList[$k][1] = $ipList[$k][1] + 1;
					$inArray = 1;
					break;
				}
				$k++;
			}
			if($inArray == 0)
			{
				$userAgentList[] = array($userAgent, 1);
			}
			
			$i++;
		}
		fclose($file);
		echo "Number Of Blacklist Requests: ".$numberOfBlocklistRequests;
		echo "<br><br>";
		echo "IP Count:<br>-----------<br><br>";
		echo "<table id=\"ipTable\" class=\"sortable\">";
		echo "<thead><tr><th>IP</th><th>Number of Occurrences</th><th>Blacklisted?</th></tr></thead><tbody>";
		foreach($ipList as $ipEntry)
		{
			if($ipEntry[1] > 10)
			{
				echo "<tr>";
				echo "<td>";
				echo $ipEntry[0];
				echo "</td><td>";
				echo $ipEntry[1];
				echo "</td><td>";
				echo checkBlacklist($ipEntry[0], $ipEntry[1]);
				echo "</td>";
				echo "</tr>";
			}
		}
		echo "</tbody></table>";



		echo "<br><br>";
		echo "User Agent Count:<br>-----------<br><br>";
		echo "<table id=\"userAgentTable\" class=\"sortable\">";
		echo "<thead><tr><th>User Agent</th><th>Number of Occurrences</th></tr></thead><tbody>";
		foreach($userAgentList as $userAgentEntry)
		{
			if($userAgentEntry[1] > 10)
			{
				echo "<tr>";
				echo "<td>";
				echo $userAgentEntry[0];
				echo "</td><td>";
				echo $userAgentEntry[1];
				echo "</tr>";
			}
		}
		echo "</tbody></table>";
	}
	else
	{
		echo "No log file found. Make sure the log is named \"log.txt\" and is in the same directory as this file.";
	}

	?>
</body>
</html>