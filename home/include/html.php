<?php
define('ASSETS_FOLDER', './assets/');

function html($level){
if ($level == 'start') { echo '<!DOCTYPE html><html lang="en"><head>'; }
if ($level == 'body') { echo '</head><body>'; }
if ($level == 'end') { echo '</body></html>'; }
}

function html_meta($title = 'Kejut Chit-Chat!', $desc = '', $author = 'Heiswayi Nrird'){
echo '
<title>'.$title.'</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="'.$desc.'">
<meta name="author" content="'.$author.'">
';
}

define('CSS_VERSION', '2.0'); // <-- DATE DDMMYYYY
function html_css(){
echo '
<link href="'.ASSETS_FOLDER.'css/ishare-no-icons.css?v='.CSS_VERSION.'" rel="stylesheet">
<link href="'.ASSETS_FOLDER.'css/ishare-responsive.css?v='.CSS_VERSION.'" rel="stylesheet">
<link href="'.ASSETS_FOLDER.'css/ishare-override.css?v='.CSS_VERSION.'" rel="stylesheet">
<link href="'.ASSETS_FOLDER.'css/ishare-custom.css?v='.CSS_VERSION.'" rel="stylesheet">
<link href="'.ASSETS_FOLDER.'css/font-awesome.min.css" rel="stylesheet">
<!--[if IE 7]>
<link href="'.ASSETS_FOLDER.'css/font-awesome-ie7.min.css" rel="stylesheet">
<![endif]-->
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
';
}

function html_favicon(){
echo '
<link rel="shortcut icon" href="'.ASSETS_FOLDER.'ico/favicon.png">
';
}

function html_jquery(){
echo '
<script src="'.ASSETS_FOLDER.'js/jquery.js"></script>
<script src="'.ASSETS_FOLDER.'js/ishare.min.js"></script>
';
}

function html_header(){
if (!defined('SITE_ROOT')) {	define('SITE_ROOT', './'); }
require(SITE_ROOT.'portal_config.php');
require_once(SITE_ROOT.'include/database.class.php');
$currentUserID = $_SESSION['current_userID'];
$db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$db->query("SELECT * FROM forum_users WHERE id='$currentUserID'");
if ($row=$db->fetch_array()) {
  $username = $row['username'];
  $realname = $row['realname'];
}
$db->close();
if ($realname !== null) { $displayname = $realname; }
else { $displayname = $username; }
    
echo '
<div id="wrap">
<div class="navbar navbar-top"><div class="navbar-inner"><div class="container">
<a class="brand" href="index.php"><div class="logo-ip"></div></a>
<div class="btn-group pull-left">
<a href="../forum" class="btn btn-inverse"><i class="icon-rss"></i> Chit-Chat Forum</a>
</div>
            
<div class="btn-group pull-right">
<a href="index.php" class="btn btn-primary"><i class="icon-home icon-white"></i> Home</a>
<a href="../forum/profile.php?id='.$currentUserID.'" class="btn btn-inverse"><i class="icon-user"></i> '.$displayname.'</a>
<button class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
  <ul class="dropdown-menu">
    <li><a href="../forum/profile.php?section=identity&id='.$currentUserID.'"><i class="icon-edit muted"></i> Edit Profile</a></li>
    <li class="divider"></li>
    <li><a href="../forum/login.php?action=out&id='.$currentUserID.'"><i class="icon-off muted"></i> Logout</a></li>
  </ul>
</div>
            
</div></div></div>
';
}

function html_footer(){
echo '
</div>
<footer><div class="container">
Kejut Chit-Chat! &copy; 2013 &#8226; Developed by <a href="http://mpp.eng.usm.my/chit-chat/forum/profile.php?id=2">Heiswayi Nrird</a> &#8226; All Rights Reserved.
</div></footer>
';
}

function html_script(){
echo '
<script src="'.ASSETS_FOLDER.'js/autogrow.js"></script>
<script src="'.ASSETS_FOLDER.'js/idle.js"></script>
<script src="'.ASSETS_FOLDER.'js/custom.js"></script> 
<script src="'.ASSETS_FOLDER.'js/jquery.NobleCount.js"></script>
<script src="'.ASSETS_FOLDER.'js/bbcode-wrap.js"></script>
';
}
?>