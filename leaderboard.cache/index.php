<?php

	/**
	 * File				: index.php
	 * Description			: Main functionality of the script.
	 * Notes			: Some variables are thrown in this script
	 *				  in a random way, although, in the origi-
	 *				  nal script it wouldn't exist because th-
	 *				  they are related to benchmark the cache.
	 *				  ----------------------------------------
	 *				  This script would go in place wherever 
	 *				  you load any database-related content.
	 */

	// Let's define where we're executing our script so we can locate our selves.
	define('BASEDIR',			realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);

	// Now, we call in our needed files.
	require_once('include'.DIRECTORY_SEPARATOR.'inc.functions.php');
	require_once('include'.DIRECTORY_SEPARATOR.'inc.functions.cache.php');
	
	$initialTime	= microtime(true);
	
	// ==============================================================
	// Main script !
	// ==============================================================
	
	$players		= array();
	
	if (cacheExpired(CACHE_TYPE_LEADERBOARD))
	{
		// Let's sleep a bit so we can emulate the database load-time.
		// 2 seconds
		sleep( 2 );
		
		// Since our cache expired, let's reload new data.
		// This will also sleep a bit for more load time.
		$players	= getTopPlayers();
		
		// Lets refill the cache with new player-data.
		cachePutStoredData( CACHE_TYPE_LEADERBOARD, $players );
		
		echo 'Refreshed cache. <hr />';
	}
	else
	{
		// We're still with a live cache!
		// Let's load it then...
		$players	= cacheGetStoredData( CACHE_TYPE_LEADERBOARD );
		
		echo 'Cache is in valid life-time. Use old data. <hr />';
	}
	
	// Just a simple table to view our data.
	echo '<table border="1"><thead><th width="30">#</th><th width="300">Player Name</th><th width="60">Kills</th></thead><tbody>';
	
	// Iterate through players and display our data
	foreach($players as $currentIndex => $currentPlayer)
	{
		echo '<tr><td>'.($currentIndex + 1).'</td><td>'.$currentPlayer['name'].'</td><td>'.$currentPlayer['kills'].'</td></tr>';
	}
	
	echo '</tbody></table>';
	
	// ==============================================================
	// End of main script.
	// ==============================================================
	
	$executionTime	= microtime(true);
	
	echo '<hr />Script execution time: <b>'.(round(($executionTime - $initialTime), 4) * 1000).'s</b>';
?>