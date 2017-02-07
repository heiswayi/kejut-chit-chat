<?php

if (!defined('FORUM_ROOT')) {	define('FORUM_ROOT', '../forum/'); }
require_once(FORUM_ROOT.'include/common.php');

if (isset($_GET['check'])) {

  $forumUserID = $forum_user['id'];
  if (!isset($_SESSION['current_userID'])) { $_SESSION['current_userID'] = $forumUserID; }
  else { if ($_SESSION['current_userID'] !== $forumUserID) { $_SESSION['current_userID'] = $forumUserID; }}

  $currentUserID = $_SESSION['current_userID'];
  if ($currentUserID > 1) { echo 'LoggedIn'; }
  else { echo 'NotLoggedIn'; }

}

?>