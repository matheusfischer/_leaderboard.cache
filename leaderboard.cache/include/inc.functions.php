<?php

	/**
	 * File				: inc.functions.php
	 * Description		: Wrapper of general functions.
	 * Notes			: - none -
	 */

	/**
	 * array getTopPlayers()
	 *
	 * @desc builds a list of random players, with kills, etc.
	 * @return array
	 * @note ignore the random gibberish names.
	 */
	function getTopPlayers()
	{
		$playerCount	= 10;
		$players		= array();
		$lpKills		= rand(0, 10000);
		
		for( $i = 0; $i < $playerCount; $i++ )
		{
			// Let's use a special sleep, cause we don't want to take too long.
			usleep(100000);
			
			$kills		= rand(0, $lpKills-1);
			
			$players[]	= array('name'		=> generateRandomPlayerName(),
								'kills'		=> $kills);
								
			$lpKills	= $kills;
		}
		
		return $players;
	}
	
	/**
	 * string generateRandomPlayerName()
	 *
	 * @desc generate random player names.
	 * @return string
	 * @note ignore the random gibberish name.
	 */
	function generateRandomPlayerName()
	{
		$dic			= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_';
		$len			= rand(4, 8);
		$name			= "";
		
		for( $i = 0; $i < $len; $i++ )
		{
			$name		.= $dic[rand(0, strlen($dic)-1)];
		}
		
		return $name;
	}

?>