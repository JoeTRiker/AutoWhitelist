<?php


   if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}
	
	global $mybb,$db;
	
	$npcGroup = $mybb->settings['npcGroup'];
	$bewerberGroup = $mybb->settings['bewerberGroup'];
	$adminGroup = $mybb->settings['adminGroup'];
	$hiddenGroup = $mybb->settings['hiddenGroup'];


	
	
	$result=$db->query("
    SELECT u.uid, u.username, u.displaygroup, u.usergroup, u.stillthere FROM mybb_users u
	WHERE NOT (u.displaygroup  LIKE '$bewerberGroup' OR u.usergroup  LIKE '$npcGroup' OR u.usergroup LIKE '$bewerberGroup'
	OR u.usergroup LIKE '$hiddenGroup'OR u.uid  LIKE '1' OR u.away LIKE '1')"
    );
	
	while($row=$db->fetch_array($result))
	{
		
		$nummer = $row['uid'];
		$update_array = array(
		"stillthere" => "0");
		$db->update_query("users", $update_array, " (as_uid = '{$nummer}' OR uid = '{$nummer}') ");
		
		
	}

	

    
	
	
?>
