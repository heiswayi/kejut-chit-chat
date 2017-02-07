<?php

if (session_id() == '') { session_start(); }

if (!defined('FORUM_ROOT')) {	define('FORUM_ROOT', '../forum/'); }
if (!defined('SITE_ROOT')) {	define('SITE_ROOT', './'); }
    
require_once(SITE_ROOT.'portal_config.php');
require_once(SITE_ROOT.'include/functions.php');
require_once(SITE_ROOT.'include/database.class.php');

function get_avatar($avatar_ext, $user_id) {
  if ($avatar_ext == '1') { return FORUM_ROOT.'img/avatars/'.$user_id.'.gif?no_cache='.random_keyx(8, TRUE); }
  if ($avatar_ext == '2') { return FORUM_ROOT.'img/avatars/'.$user_id.'.jpg?no_cache='.random_keyx(8, TRUE); }
  if ($avatar_ext == '3') { return FORUM_ROOT.'img/avatars/'.$user_id.'.png?no_cache='.random_keyx(8, TRUE); }
  if ($avatar_ext == '0') { return SITE_ROOT.'assets/img/default-avatar.png'; }
}

if (isset($_GET['load']) && !empty($_GET['load'])) {
    
    $latest_shouts = $_GET['load'];
    $db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
    $db->query("SELECT * FROM ip_shouts ORDER BY id DESC LIMIT $latest_shouts");
    
    while($row=$db->fetch_assoc()) {
    
      $get_shoutID = $row['id'];
      $get_userID =  $row['user_id'];
      $get_shoutMsg = $row['shout_msg'];
      $get_sTime = $row['shout_time'];
    
      $dbf = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
      $dbf->query("SELECT * FROM forum_users WHERE id='$get_userID'");
    
      if ($rowf=$dbf->fetch_array()) {
        
        $get_groupID = $rowf['group_id'];
        $get_username = $rowf['username'];
        $get_realname = $rowf['realname'];
        $get_title = $rowf['title'];
        $get_location = $rowf['location'];
        $get_registered = $rowf['registered'];
        $get_url = $rowf['url'];
        $get_facebook = $rowf['facebook'];
        $get_twitter = $rowf['twitter'];
        $show_avatar = $rowf['show_avatars'];
        $avatar_type = $rowf['avatar'];
        
        if ($get_facebook == null) {
          $facebook_url = '';
        } else if ((strpos($get_facebook, "http://") === 0) || (strpos($get_facebook, "https://") === 0)){
          $facebook_url = '<a href="'.$get_facebook.'">'.$get_facebook.'</a>';
        } else {
          $facebook_url = '<a href="http://facebook.com/'.$get_facebook.'">http://facebook.com/'.$get_facebook.'</a>';
        }
        
        if ($get_twitter == null) {
          $twitter_url = '';
        } else if ((strpos($get_twitter, "http://") === 0) || (strpos($get_twitter, "https://") === 0)){
          $twitter_url = '<a href="'.$get_twitter.'">'.$get_twitter.'</a>';
        } else {
          $twitter_url = '<a href="http://twitter.com/'.$get_twitter.'">http://twitter.com/'.$get_twitter.'</a>';
        }
        
        if ($get_url == null) {
          $website = '';
        } else if ((strpos($get_url, "http://") === 0) || (strpos($get_url, "https://") === 0)){
          $website = '<a href="'.$get_url.'">'.$get_url.'</a>';
        } else {
          $website = '<a href="http://'.$get_url.'">http://'.$get_url.'</a>';
        }
        
      }
      
      $dbf->close();

      echo '<li class="left">';
    
      echo '<a href="profile.php?id='.$get_userID.'"><img class="avatar" alt="'.$get_username.'" src="'.get_avatar($avatar_type, $get_userID).'"></a>';
    
      echo '<span class="message"><span class="arrow"></span>';
      
      if ($get_realname == null) {
        echo '<span class="from"><a href="profile.php?id='.$get_userID.'" class="user-name">@'.$get_username.'</a> ';
      } else {
        echo '<span class="from"><a href="profile.php?id='.$get_userID.'" class="user-name">'.$get_realname.'</a> ';
      }
      
      if ($get_title !== null) {
        echo '<span class="forum-title"><em>'.$get_title.'</em></span></span> ';
      }
    
      echo '<span class="time muted"><small>'.timeAgo($get_sTime).'</small></span>';
      
      if ($_SESSION['current_userID'] !== $get_userID) {
        echo '<span class="pull-right">';
        echo '<button class="btn btn-mini tip-top" id="rtshout-'.$get_shoutID.'" onClick="rtshout(\''.$get_shoutID.'\',\''.$get_username.'\');" title="Reshout"><i class="icon-share"></i> RT</button> ';
        echo '<button class="btn btn-mini" id="mention-'.$get_shoutID.'" onClick="insertNickname(\''.$get_username.'\');"><i class="icon-circle"></i> Mention</button>';
        echo '</span>';
      }
    
      echo '<span class="text" id="msg-'.$get_shoutID.'">'.stripslashes(rtrim(clickable(bbCode($get_shoutMsg)))).'</span>';
    
      echo '</span></li>';

  }
  
  echo '
    <script>
    $(document).ready(function () { // START DOCUMENT.READY
    
    $(".link-tip, .tip-top").tooltip();
    
    }); // END DCOUMENT.READY
    
    function rtshout(msgid,user){      
      $.ajax({
        type: "GET", url: "subfiles/shoutbox_retweet.php?retweet=" + urlencode(msgid),
        success: function(html){
          if (html !== "KO") { $("#shoutTextarea").val("RT @"+user+": " + html); }
        }
      });
    }
    function urlencode(a) {
      a = (a + "").toString();
      return encodeURIComponent(a).replace(/!/g, "%21").replace(/\'/g, "%27").replace(/\(/g, "%28").replace(/\)/g, "%29").replace(/\*/g, "%2A").replace(/%20/g, "+")
    }
    </script>
    ';
  
}
  
?>