<?php


   if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}
	
	global $mybb;
	

	


	
	
	$result=$db->query("
    SELECT u.uid, u.username, u.displaygroup, u.usergroup, u.stillthere FROM mybb_users u
    WHERE NOT (u.displaygroup  LIKE '2' OR u.uid  LIKE '1' OR u.usergroup LIKE '2' OR u.stillthere LIKE '1' OR u.away LIKE '1' )"
    );
	
	while($row=$db->fetch_array($result))
	{
	
	
		$nummer = $row['uid'];
		$db->query("INSERT INTO mybb_users_archive SELECT * FROM mybb_users WHERE uid='{$nummer}'");
		$db->query("INSERT INTO mybb_userfields_archive SELECT * FROM mybb_userfields WHERE ufid='{$nummer}'");
		
		$db->query("DELETE FROM mybb_users WHERE uid='{$nummer}'");
		$db->query("DELETE FROM mybb_userfields WHERE ufid='{$nummer}'");
		
		
	}

	

    
	
	
?>
