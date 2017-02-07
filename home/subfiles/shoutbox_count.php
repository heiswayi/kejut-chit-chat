<?php

if (!defined('SITE_ROOT')) {	define('SITE_ROOT', '../'); }

require_once(SITE_ROOT.'portal_config.php');
require_once(SITE_ROOT.'include/database.class.php');

// Removing messages older than X days
$keep_period1 = time() - (86400 * KEEP_SHOUTS);
$keep_period2 = time() - (86400 * KEEP_REQUESTS);
$keep_period3 = time() - (86400 * KEEP_UPDATES);
$keepdb = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$keepq1 = $keepdb->query("SELECT id FROM ip_shouts WHERE shout_time < $keep_period1");
if ($keepdb->num($keepq1) > 0) { $keepdb->query("DELETE FROM ip_shouts WHERE shout_time < $keep_period1"); }
$keepq2 = $keepdb->query("SELECT id FROM ip_requests WHERE shout_time < $keep_period2");
if ($keepdb->num($keepq2) > 0) { $keepdb->query("DELETE FROM ip_requests WHERE shout_time < $keep_period2"); }    
$keepq3 = $keepdb->query("SELECT id FROM ip_updates WHERE shout_time < $keep_period3");
if ($keepdb->num($keepq3) > 0) { $keepdb->query("DELETE FROM ip_updates WHERE shout_time < $keep_period3"); }
$keepdb->close();
// End of Removing messages older than X days

$db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$db->query("SELECT COUNT(id) FROM ip_shouts"); 

echo implode($db->fetch_assoc());

$db->close();

?>