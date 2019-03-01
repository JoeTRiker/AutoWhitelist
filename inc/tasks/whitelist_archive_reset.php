<?php


   if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}
	
	global $mybb,$db;
	
	if($db->table_exists('users_archive'))
	{
		$db->query("DROP TABLE mybb_users_archive");
		$db->query("CREATE TABLE mybb_users_archive LIKE mybb_users");
	
	
	}
	
	if($db->table_exists('userfields_archive'))
	{
		
		$db->query("DROP TABLE mybb_userfields_archive");
		$db->query("CREATE TABLE mybb_userfields_archive LIKE mybb_userfields");
	
	
	}

	

    
	
	
?>
