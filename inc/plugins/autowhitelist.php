<?php
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.");
}

$plugins->add_hook("global_intermediate", "autowhitelist_pleasewhite");

function autowhitelist_info()
{
	return array(
		"name"			=> "AutoWhitelist",
		"description"	=> "Eine autlomatisierte Whitelist",
		"website"		=> "https://hiraeth.at/ashes",
		"author"		=> "Joe Riker",
		"authorsite"	=> "https://hiraeth.at/ashes",
		"version"		=> "2.0",
		"compatibility" => "*"
	);
}

function autowhitelist_install(){
	
	global $db, $mybb;
	
	
	//Tabellen einfügen
	
	$db->query("ALTER TABLE `".TABLE_PREFIX."users` ADD `stillthere` tinyint(1) NOT NULL DEFAULT '1';");
	
	if(!$db->table_exists('users_archive'))
	{
		$db->query("CREATE TABLE mybb_users_archive LIKE mybb_users");
	
	
	}
	
	if(!$db->table_exists('userfields_archive'))
	{
		$db->query("CREATE TABLE mybb_userfields_archive LIKE mybb_userfields");
	
	
	}
	
	
	//Einstellungen einfügen
	
	$setting_group = array(
      'name' => 'autowhitelist',
      'title' => 'AutoWhitelist',
      'description' => 'Einstellungen für das AutoWhitelist-Plugin',
      'disporder' => 99, // The order your setting group will display
      'isdefault' => 0
	);
	$gid = $db->insert_query("settinggroups", $setting_group);
	
	$setting_array = array(
      'useAccountswitcher' => array(
          'title' => 'Accountswitcher benutzen',
          'description' => 'Gib an, ob du die Whitelist mit dem Add-In für Doyle´s erweiterten Accountswitcher verwenden möchtest.',
          'optionscode' => 'yesno',
          'value' => '0', // Default
          'disporder' => 1
      ),
	  'adminGroup' => array(
          'title' => 'Die ID der Admingruppe',
          'description' => 'Gib die ID der Admingruppe an. (Default sollte in den meisten Fällen passen.)',
          'optionscode' => 'text',
          'value' => '4', // Default
          'disporder' => 2
      ),
	  'bewerberGroup' => array(
          'title' => 'Die ID der Bewerbergruppe',
          'description' => 'Gib die ID der Bewerbergruppe an.',
          'optionscode' => 'text',
          'value' => '1', // Default
          'disporder' => 3
      ),
	  'npcGroup' => array(
          'title' => 'Die ID der NPC-Gruppe',
          'description' => 'Gib die ID der NPC-Gruppe an.',
          'optionscode' => 'text',
          'value' => '1', // Default
          'disporder' => 4
      ),
	  'hiddenGroup' => array(
          'title' => 'Die ID der Gruppe für versteckte Accounts',
          'description' => 'Gib die ID der Gruppe für versteckte Accounts an.',
          'optionscode' => 'text',
          'value' => '1', // Default
          'disporder' => 5
      ),
      
	);

  foreach($setting_array as $name => $setting)
  {
      $setting['name'] = $name;
      $setting['gid'] = $gid;

      $db->insert_query('settings', $setting);

  }
	
	//Templates einfügen
	
	$insert_array = array(
		'title'		=> 'whitelist',
		'template'	=> $db->escape_string('<html>
<head>
<title>Wer ist denn noch da?</title>
{$headerinclude}
</head>
<body>
{$header}
<div align="justify" class="trow2" style="padding: 10px">
	<h1 style="font-size: 20px;">Whitelist</h1>
	<center><strong>!</strong></center>
	<table style="width: 100%;" cellpadding="5" cellspacing="0"><tr><td width="49%"><h4>Rückgemeldet</h4></td><td width="49%"><h4>Nicht Rückgemeldet</h4></td></tr>
		<tr><td width="49%" valign="top"><center>{$stillthere}</center></td><td width="49%" valign="top"><center>{$maybegone}</center></td></tr>
</table>
</div>
{$footer}
</body>
</html>'),
		'sid'		=> '-1',
		'version'	=> '',
		'dateline'	=> TIME_NOW
	);
	$db->insert_query("templates", $insert_array);
	
	$insert_array = array(
		'title'		=> 'whitelist_makewhite_bit',
		'template'	=> $db->escape_string('                        <div class="pm_alert"><div style=\"font-size: 10px; float: right;\">Du hast dich noch nicht zurückgemeldet! Bitte tu das <a href="whitelist_makewhite.php" target=\"blank\">HIER</a>!</div>
	<br style=\"float: clear;\" /></div>'),
		'sid'		=> '-1',
		'version'	=> '',
		'dateline'	=> TIME_NOW
	);
	$db->insert_query("templates", $insert_array);
	
}

function autowhitelist_is_installed()
{
  global $db;
  if($db->field_exists("stillthere", "users"))
  {
      return true;
  }
  return false;
}

function autowhitelist_uninstall(){
	
	global $db, $mybb;
	
	//Tabellen Löschen
	if($db->field_exists("stillthere", "users"))
		{
			$db->drop_column("users", "stillthere");
		}
	
	// Einstellungen entfernen
	$db->delete_query('settings', "name IN ('useAccountswitcher')");
	$db->delete_query('settinggroups', "name = 'autowhitelist'");
	rebuild_settings();
	
	//Templates entfernen	
	$db->delete_query("templates", "title IN('whitelist','whitelist_makewhite_bit')");
}

function autowhitelist_activate(){
	
	
}

function autowhitelist_deactivate(){
	
	

}

function autowhitelist_pleasewhite() {
	global $mybb, $db, $pleasewhitelist, $templates;
	
	if($mybb->user['uid'] != 0 && $mybb->user['stillthere'] == 0) eval('$pleasewhitelist = "'.$templates->get('whitelist_makewhite_bit').'";');

}