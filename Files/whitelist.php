<?php
   define("IN_MYBB", 1);
        require_once "./global.php";
        require_once MYBB_ROOT."inc/class_parser.php";
    add_breadcrumb("Whitelist", "whitelist.php");
	
	global $mybb,$db;
	
	

	
if(!$mybb->input['action'])
{
	
	$npcGroup = $mybb->settings['npcGroup'];
	$bewerberGroup = $mybb->settings['bewerberGroup'];
	$adminGroup = $mybb->settings['adminGroup'];
	$hiddenGroup = $mybb->settings['hiddenGroup'];
	
	if ( $mybb->user['uid'] == 0) error_no_permission();
	
	
	
	
	$result=$db->query("
    SELECT u.uid, u.username, u.displaygroup, u.usergroup, u.stillthere FROM mybb_users u
    WHERE NOT (u.displaygroup  LIKE '$bewerberGroup' OR u.usergroup  LIKE '$npcGroup' OR u.usergroup LIKE '$bewerberGroup'OR u.usergroup LIKE '$hiddenGroup')"
    );
	
	$stillthere = '';
	$maybegone = '';
	while($row=$db->fetch_array($result))
	{
		$userid = $row['uid'];
        $usernamecolor = format_name($row['username'], $row['usergroup'], $row['displaygroup']);
        $usernamelink = build_profile_link($usernamecolor, $userid);

        $username = $usernamelink;
		
		if($row['stillthere'] == 1) $stillthere .= "<span class=\"smalltext\">$username </span> <br />";
		else $maybegone .= "<span class=\"smalltext\">$username </span> <br />";
		
		
	}

	

    eval("\$page = \"".$templates->get("whitelist")."\";");
    output_page($page);
	
	
	}
	
	
?>

