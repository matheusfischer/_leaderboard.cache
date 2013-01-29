<?php

	/**
	 * File				: inc.functions.cache.php
	 * Description		: Wrapper of cache-related functions.
	 * Notes			: All functions declared here -should- be prefixed with
	 *                    'cache'. Just for "clean-code" purposes.
	 */

	// Define some constants
	define('CACHE_INDEX',				BASEDIR.'data'.DIRECTORY_SEPARATOR.'cache.dat');
	
	define('CACHE_TYPE_LEADERBOARD',	'leadeboard');
	 
	/**
	 * bool cacheCreateCacheIndex()
	 *  
	 * @desc if we have a cache file, return true. if we don't, create it and return true aswell.
	 * @return bool
	 */
	function cacheCreateCacheIndex()
	{
		if (!file_exists(CACHE_INDEX))
		{
			file_put_contents(CACHE_INDEX, cacheSerializeData(array()));
			return true;
		}
		else
			return true;
	}
	
	/**
	 * string cacheSerializeData()
	 *  
	 * @desc serialize our data before inserting into file.
	 * @param $data mixed
	 * @return string
	 */
	function cacheSerializeData( $data )
	{
		return serialize($data);
	}

	/**
	 * mixed cacheUnserializeData()
	 *  
	 * @desc unserialize our data after reading a cache-file.
	 * @param $data mixed
	 * @return mixed
	 */
	function cacheUnserializeData( $data )
	{
		return unserialize($data);
	}
	
	/**
	 * void cachePutData( string $name, mixed $data )
	 *
	 * @desc 
	 * @param $name string
	 * @param $data mixed
	 * @return void
	 */
	function cachePutData( $name, $data )
	{
		if (cacheCreateCacheIndex())
		{
			$contents			= file_get_contents(CACHE_INDEX);
			
			if ($contents)
			{
				$cache			= cacheUnserializeData($contents);
				$cache[$name]	= $data;
				
				return file_put_contents(CACHE_INDEX, cacheSerializeData($cache));
			}
			else
				return false;
		}
		else
			return false;
	}
	
	/**
	 * mixed cacheGetData( string $name )
	 *
	 * @desc Get data from a stored cache.
	 * @param $name string
	 * @return mixed
	 */
	function cacheGetData( $name )
	{
		if (cacheCreateCacheIndex())
		{
			$contents			= file_get_contents(CACHE_INDEX);
			
			if ($contents)
			{
				$cache			= cacheUnserializeData($contents);
				
				return (isset($cache[$name])) ? $cache[$name] : false;
			}
			else
				return false;
		}
		else
			return false;
	}
	
	/**
	 * void cacheCreate( string $name )
	 *
	 *
	 */
	function cacheCreate( $name )
	{
		$cache			= array('last_update_time'		=> time(),
								'lifetime'				=> 60 * 60, // 1h
								'data'					=> array());
								
		cachePutData( $name, $cache );
	}
	
	/**
	 * bool cacheExpired( string $name )
	 *
	 * @desc Check if $name is expired. if $name does not exist, create it.
	 * @param $name string
	 * @return bool
	 */
	function cacheExpired( $name )
	{
		$cache			= cacheGetData($name);
		if ($cache)
		{
			$now		= time();
			if (($now - $cache['last_update_time']) > $cache['lifetime'])
			{
				$cache['last_update_time']	= $now;
				return true;
			}
			else
				return false;
		}
		else
		{
			cacheCreate($name);
			return true;
		}
	}
	
	/**
	 * mixed cacheGetStoredData( string $name )
	 *
	 * @desc returns the data stored in $name. if doens't exist, return false
	 * @param string $name
	 * @return mixed
	 */
	function cacheGetStoredData( $name )
	{
		$cache			= cacheGetData($name);
		
		if ($cache)
		{
			return $cache['data'];
		}
		else
			return false;
	}
	
	/**
	 * mixed cachePutStoredData( string $name, mixed $data )
	 *
	 * @desc puts $data in $name. if $name doesn't exist, return false
	 * @param string $name
	 * @param mixed $data
	 * @return mixed
	 */
	function cachePutStoredData( $name, $data )
	{
		$cache			= cacheGetData( $name );
		
		if ($cache)
		{
			$cache['data'] = $data;
			
			return cachePutData( $name, $cache );
		}
		else
			return false;
	}
	
?>