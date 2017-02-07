<?php

if (!defined('FORUM_ROOT')) {	define('FORUM_ROOT', '../forum/'); }
if (!defined('SITE_ROOT')) {	define('SITE_ROOT', './'); }

require_once(FORUM_ROOT.'include/common.php'); // (required) // session_start()
require_once(SITE_ROOT.'include/html.php'); // HTML structures

// Check current user session ID
$forumUserID = $forum_user['id'];
if ($forum_user['is_guest']) { header('Location: login.php'); } // If not logged in, forward to login.php

// Load top level HTML structures
html('start');
html_meta();
html_css();
html_favicon();
html_jquery();
html('body');
html_header();

?>

<div class="container">
    
<div class="row-fluid">
    
<div class="span8" style="margin:0 auto;float:none">

<?php
if ($forum_config['o_announcement'] == 1) {
echo '<div class="alert alert-block"><h4>'.$forum_config['o_announcement_heading'].'</h4>'.$forum_config['o_announcement_message'].'</div>';
}
?>

<div id="global-content" style="padding:20px;border:1px solid #31b0d5;background:#fff;">
<div id="global-title"><i class="icon-comments-alt"></i> Kejut Chit-Chat!</div>
    
<?php
if (!defined('SITE_ROOT')) {	define('SITE_ROOT', './'); }
require_once(SITE_ROOT.'portal_config.php');
require_once(SITE_ROOT.'include/functions.php');
require_once(SITE_ROOT.'include/database.class.php');
function get_avatar($avatar_ext, $user_id) {
  if ($avatar_ext == '1') { return FORUM_ROOT.'img/avatars/'.$user_id.'.gif'; }
  if ($avatar_ext == '2') { return FORUM_ROOT.'img/avatars/'.$user_id.'.jpg'; }
  if ($avatar_ext == '3') { return FORUM_ROOT.'img/avatars/'.$user_id.'.png'; }
  if ($avatar_ext == '0') { return SITE_ROOT.'assets/img/default-avatar.png'; }
}
$db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
if (!empty($_GET['q']) && isset($_GET['q'])) {
  $thisHashtag = $db->prot(htmlspecialchars($_GET['q']));
  $findHashtag = '#'.$thisHashtag;
  $db->query("SELECT COUNT(id) FROM ip_shouts WHERE shout_msg LIKE '%$findHashtag%'");
  $total_hashtag = implode($db->fetch_assoc());
  echo '<div class="well well-small">Found <strong>'.$total_hashtag.'</strong> message(s) with hashtag: <code>'.stripslashes(rtrim($findHashtag)).'</code></div>';
  echo '<ul id="chat" class="chat">';
  $db->query("SELECT * FROM ip_shouts WHERE shout_msg LIKE '%$findHashtag%' ORDER BY id DESC LIMIT 50");
  while($row=$db->fetch_assoc()) {
    $get_shoutID = $row['id'];
    $get_userID =  $row['user_id'];
    $get_shoutMsg = $row['shout_msg'];
    $get_sTime = $row['shout_time'];
    $dbf = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
    $dbf->query("SELECT * FROM forum_users WHERE id='$get_userID'");
    if ($rowf=$dbf->fetch_array()) {
      $get_username = $rowf['username'];
      $get_realname = $rowf['realname'];
      $get_title = $rowf['title'];
      $show_avatar = $rowf['show_avatars'];
      $avatar_type = $rowf['avatar'];
    }
    $dbf->close();
      
    echo '<li class="left">';
    echo '<a href="'.FORUM_ROOT.'profile.php?section=about&id='.$get_userID.'"><img class="avatar" alt="'.$get_username.'" src="'.get_avatar($avatar_type, $get_userID).'"></a>';
    echo '<span class="message"><span class="arrow"></span>';
    if ($get_realname == null) {
      echo '<span class="from"><a href="'.FORUM_ROOT.'profile.php?section=about&id='.$get_userID.'" class="user-name">@'.$get_username.'</a> ';
    } else {
      echo '<span class="from"><a href="'.FORUM_ROOT.'profile.php?section=about&id='.$get_userID.'" class="user-name">'.$get_realname.'</a> ';
    }
    if ($get_title !== null) {
      echo '<span class="forum-title"><em>'.$get_title.'</em></span></span> ';
    }
    echo '<span class="time muted"><small>'.timeAgo($get_sTime).'</small></span>';
    echo '<span class="text" id="msg-'.$get_shoutID.'">'.stripslashes(rtrim(clickable(bbCode($get_shoutMsg)))).'</span>';
    echo '</span></li>';
  }
  echo '</ul>';
  $db->close();
} else {
  header('Location: index.php');
}
?>

</div>
      
</div>    
   
</div><!-- /.row-fluid -->

</div><!-- /.container -->
    
<?php html_footer(); ?>

<?php html_script(); ?>
<script>
var checkUserTimer = setTimeout("checkSession()", 1000);
function checkSession(){
  clearTimeout(checkUserTimer);
  $.ajax({
    type: 'GET', url: 'session.php?check=1&i=' + Math.random(),
    success: function(data){
      if (data == 'NotLoggedIn') { window.location.replace('login.php'); }
      else { checkUserTimer = setTimeout("checkSession()", 1000); }
    }
  });
}
</script>

<?php echo html('end'); ?>
