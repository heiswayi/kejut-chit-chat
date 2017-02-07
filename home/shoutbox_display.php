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

function emptybbCode($input) {
  if (preg_match('/\{\:(\s*)\:\}/i',$input) || 
      preg_match('/\[\:(\s*)\:\]/i',$input) || 
      preg_match('/\[\;(\s*)\;\]/i',$input) || 
      preg_match('/\{\;(\s*)\;\}/i',$input) || 
      preg_match('/\[rt\](\s*)\[\/rt\]/i',$input) || 
      preg_match('/\[quote\](\s*)\[\/quote\]/i',$input) || 
      preg_match('/\[purple\](\s*)\[\/purple\]/i',$input) || 
      preg_match('/\[blue\](\s*)\[\/blue\]/i',$input) || 
      preg_match('/\[teal\](\s*)\[\/teal\]/i',$input) || 
      preg_match('/\[green\](\s*)\[\/green\]/i',$input) || 
      preg_match('/\[orange\](\s*)\[\/orange\]/i',$input) ||
      preg_match('/\[pink\](\s*)\[\/pink\]/i',$input) || 
      preg_match('/\[red\](\s*)\[\/red\]/is',$input)) {
    return true;
  } else {
    return false;
  }
}

if (isset($_GET['display']) && $_GET['display'] == 1) {

  populate_shoutbox();

} else if (isset($_POST['shoutMsg']) && isset($_POST['userID'])) {

  $db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);

  $shoutTime = time();
  $shoutMsg = $db->prot(htmlspecialchars($_POST['shoutMsg']));
  $userID = $db->prot(htmlspecialchars($_POST['userID']));
  
  if ($shoutMsg == '' || strlen($shoutMsg) == 0) {
    echo 'ERROR! You shout nothing! Say something.';
  } else if (emptybbCode($shoutMsg)) {
    echo 'ERROR! bbCode tags cannot be empty.';
  } else if (preg_match('/!request/i', $shoutMsg) && preg_match('/!update/i', $shoutMsg)) {
    echo 'ERROR! !update and !request cannot be together in one shout.';
  } else if (preg_match('/!request/i', $shoutMsg)) {
    $requestMsg = preg_replace('/!request/i', '', $shoutMsg);
    if ($requestMsg !== '') {
      $db->query("INSERT INTO ip_requests (shout_time, shout_msg, user_id) VALUES ('$shoutTime', '$requestMsg', '$userID')");
      $db->query("INSERT INTO ip_shouts (shout_time, shout_msg, user_id) VALUES ('$shoutTime', '$shoutMsg', '$userID')");
      echo 'OK';
    } else {
      echo 'ERROR! Request message cannot be empty!';
    }
  } else if (preg_match('/!update/i', $shoutMsg)) {
    $updateMsg = preg_replace('/!update/i', '', $shoutMsg);
    if ($updateMsg !== '') {
      $db->query("INSERT INTO ip_updates (shout_time, shout_msg, user_id) VALUES ('$shoutTime', '$updateMsg', '$userID')");
      $db->query("INSERT INTO ip_shouts (shout_time, shout_msg, user_id) VALUES ('$shoutTime', '$shoutMsg', '$userID')");
      echo 'OK';
    } else {
      echo 'ERROR! Update message cannot be empty!';
    }
  } else {
    $db->query("INSERT INTO ip_shouts (shout_time, shout_msg, user_id) VALUES ('$shoutTime', '$shoutMsg', '$userID')");
    echo 'OK';
  }
  
  $db->close();

} else { header('Location: '.SITE_ROOT.'404.php'); }

function populate_shoutbox() {
   
    $db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
    $db->query("SELECT * FROM ip_shouts ORDER BY id DESC LIMIT 100");
    
    echo '<ul id="chat" class="chat">';
    
    $count_shout = 0;
    
    while($row=$db->fetch_assoc()) {
    
      $count_shout++;
    
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
      
      if ($_SESSION['current_userID'] == $get_userID) {
        if ($count_shout == 20) { echo '<li id="lastShout" class="right">'; }
        else { echo '<li class="right">'; }
      } else {
        if ($count_shout == 20) { echo '<li id="lastShout" class="left">'; }
        else { echo '<li class="left">'; }
      }
    
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
  
    if ($count_shout == 100) {
      echo '<div style="margin-top:10px;padding:10px;text-align:center;" id="more-'.$get_shoutID.'" class="morebox"><a href="#" id="'.$get_shoutID.'" class="btn btn-small btn-inverse more"><i class="icon-arrow-down icon-white"></i> Load more...</a></div>';
    }
    
    echo '</ul>';
    $db->close();
    
    echo '
    <script>
    $(document).ready(function () { // START DOCUMENT.READY
    
    $(".link-tip, .tip-top").tooltip();
    $(".more").click(function(e){
    e.preventDefault();
    var ID = $(this).attr("id");
    if (ID){
      $("#more-"+ID).html("<div class=\"loader\" style=\"margin-top:10px\"></div>");
      $.ajax({
        type: "GET", url: "subfiles/shoutbox_more.php?lastid=" + urlencode(ID),
        success: function(html){ $("ul#chat").append(html).fadeIn(); $("#more-"+ID).remove(); }
      });
    } else {
      $(".morebox").html("The End");
    }
    });
    
    }); // END DCOUMENT.READY
    
    function urlencode(a) {
      a = (a + "").toString();
      return encodeURIComponent(a).replace(/!/g, "%21").replace(/\'/g, "%27").replace(/\(/g, "%28").replace(/\)/g, "%29").replace(/\*/g, "%2A").replace(/%20/g, "+")
    }
    function rtshout(msgid,user){      
      $.ajax({
        type: "GET", url: "subfiles/shoutbox_retweet.php?retweet=" + urlencode(msgid),
        success: function(html){
          if (html !== "KO") { $("#shoutTextarea").val("RT @"+user+": " + html); }
        }
      });
    }
    function insertNickname(nickname){
      var currentText = document.getElementById("shoutTextarea");
      var smileyWithPadding = " @" + nickname + " ";
      currentText.value += smileyWithPadding;
    }
    </script>
    ';
}
?>