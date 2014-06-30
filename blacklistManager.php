<?php

	function checkBlacklist($ip, $numOccurrences)
	{
	
		$blacklistFile = "blacklist/blacklist.log";
		$blacklist = fopen($blacklistFile, "r");
		
		if($blacklist == false)
		{
			$blacklist = fopen($blacklistFile, "w");
			fclose($blacklist);
			$blacklist = fopen($blacklistFile, "r");
		}
		
		$ipData = null;
		while(($line = fgets($blacklist)) != false)
		{
			$lineArray = explode(":", $line);
			if($ip === $lineArray[0])
			{
				$ipData = $lineArray;
				break;
			}
		}
		fclose($blacklist);
		
		$status = null; $recordAge = null;
		
		if($ipData != null)
		{
			$status = $ipData[1];
			$recordAge = time() - $ipData[2];
		}
		
		// If the record is over 30 days old and is not known to be bad, check with the blocking system.
		if( ($ipData === null) || ( ($recordAge > 2592000) && ($status != "bad") ) )
		{
			$status = "unknown";
			if($numOccurrences > 100)
			{
				$requestUrl = "http://api.blocklist.de/api.php?ip=".$ip;
				//echo $requestUrl;
				$answer = file_get_contents($requestUrl);
				$numberOfBlocklistRequests++;
				
				if($answer === false)
				{
					return $status;
				}
				
				$firstExplosion = explode("attacks: ", $answer);
				$secondExplosion = explode("reports: ", $firstExplosion[1]);
				$attacks = preg_replace('/\s+/', '', $secondExplosion[0]);
				$reports = preg_replace('/\s+/', '', $secondExplosion[1]);
				
				if( ($attacks != 0) || ($reports != 0))
				{
					$status = "bad";
				}
				else
				{
					$status = "ok";
				}
				if(!updateBlacklist($ip, $status, time()))
				{
					insertIntoBlacklist($ip, $status, time());
				}
				sleep(1);
			}
		}
		
		return $status;
		
	}
	
	function updateBlacklist($ip, $status, $time)
	{
		$updateString = $ip.":".$status.":".$time;
		
		
		
		$reading = fopen('blacklist/blacklist.log', 'r');
		$writing = fopen('blacklist/temp.tmp', 'w');

		$replaced = false;

		while (!feof($reading)) {
		  $line = fgets($reading);
		  if (stristr($line,$ip)) {
			$line = $updateString."\n";
			$replaced = true;
		  }
		  fputs($writing, $line);
		}
		fclose($reading); fclose($writing);
		// might as well not overwrite the file if we didn't replace anything
		if ($replaced) 
		{
		  rename('blacklist/temp.tmp', 'blacklist/blacklist.log');
		} else {
		  unlink('blacklist/temp.tmp');
		}
		
		return $replaced;
	}
	
	function insertIntoBlacklist($ip, $status, $time)
	{
		$updateString = $ip.":".$status.":".$time."\n";
		$blacklist = fopen("blacklist/blacklist.log", "a");
		$result = fwrite($blacklist, $updateString);
		fclose($blacklist);
		return $result;
	}
?>