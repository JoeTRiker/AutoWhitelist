<?php
   define("IN_MYBB", 1);
        require_once "./global.php";
        require_once MYBB_ROOT."inc/class_parser.php";
  
	
	global $mybb,$db;

	$useAccountswitcher = $mybb->settings['useAccountswitcher'];
	
	


	
	$nummer = $mybb->user['uid'];
	
	if($useAccountswitcher)
	{
		if($mybb->user['as_uid'] != '0') $nummer =  $mybb->user['as_uid'];
	}
	
	
	 $update_array = array(
		"stillthere" => "1");
		
	
	if($useAccountswitcher)
	{
		$db->update_query("users", $update_array, " (as_uid = '{$nummer}' OR uid = '{$nummer}') ");
	}
		
	else 
	{
		$db->update_query("users", $update_array, " ( uid = '{$nummer}') ");
		
	}
	
	
		
		

		
		
	

	$url = $mybb->settings['bburl'];
	//echo($url);

    header('Location: '.$url);

?>

