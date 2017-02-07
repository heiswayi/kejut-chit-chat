<?php

if (!defined('SITE_ROOT')) {	define('SITE_ROOT', '../'); }

require_once(SITE_ROOT.'portal_config.php');
require_once(SITE_ROOT.'include/database.class.php');

$db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
//$db->query("SELECT ident FROM forum_online ORDER BY ident");
$db->query("SELECT username FROM forum_users ORDER BY username");

echo '[';
while ($row=$db->fetch_array()) {
  $username=$row['username'];
  if ($username !== 'Guest') {
    echo '"@'.$username.'",';
  }
}
echo ']';

$db->close();

?>
