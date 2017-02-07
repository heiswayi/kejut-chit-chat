<?php

if (!defined('FORUM_ROOT')) {	define('FORUM_ROOT', '../forum/'); }
if (!defined('SITE_ROOT')) {	define('SITE_ROOT', './'); }

require_once(FORUM_ROOT.'include/common.php'); // (required) // session_start()
require_once(SITE_ROOT.'include/html.php'); // HTML structures
require_once(SITE_ROOT.'portal_config.php');

// Check current user session ID
if ($forum_user['is_guest']) { header('Location: login.php'); }
if ($forum_user['group_id'] != 1) { header('Location: index.php'); }

if (!isset($_SESSION['current_userID'])) { $_SESSION['current_userID'] = $forum_user['id']; }
else { $_SESSION['current_userID'] = $forum_user['id']; }

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
   
<?php

$config['table'] = "forum_users";
$config['nicefields'] = false; //true or false | "Field Name" or "field_name"
$config['perpage'] = 100;
$config['showpagenumbers'] = true; //true or false
$config['showprevnext'] = true; //true or false

/******************************************/
//SHOULDN'T HAVE TO TOUCH ANYTHING BELOW...
//except maybe the html echos for pagination and arrow image file near end of file.

include('include/pagination.php');
$Pagination = new Pagination();

// Connect to Database
$connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die ("Could not connect to server ... \n" . mysql_error ());
mysql_select_db(DB_NAME) or die ("Could not connect to database ... \n" . mysql_error ());

//get total rows
$totalrows = mysql_fetch_array(mysql_query("SELECT count(*) as total FROM `".$config['table']."`"));

$q_total_rows = mysql_query("SELECT id FROM forum_users") or die(mysql_error());
$total_rows = mysql_num_rows($q_total_rows) - 1;

echo '<div class="menu">';
echo '<p><strong>MENU:</strong> <a href="../mysql.php" target="_blank" class="pop-login-data" title="Login Info" data-content="System: MySQL | Server: localhost | Username: mpp_web | Password: mPPWeb | Database: mpp">Database (MySQL) Manager</a></p>';
echo '<p><strong>DISPLAY MODE:</strong> 100 rows/page | <strong>TOTAL USERS:</strong> '.$total_rows.'</p>';
echo '</div>';

//limit per page, what is current page, define first record for page
$limit = $config['perpage'];
if(isset($_GET['page']) && is_numeric(trim($_GET['page']))){$page = mysql_real_escape_string($_GET['page']);}else{$page = 1;}
$startrow = $Pagination->getStartRow($page,$limit);

//create page links
if($config['showpagenumbers'] == true){
	$pagination_links = $Pagination->showPageNumbers($totalrows['total'],$page,$limit);
}else{$pagination_links=null;}

if($config['showprevnext'] == true){
	$prev_link = $Pagination->showPrev($totalrows['total'],$page,$limit);
	$next_link = $Pagination->showNext($totalrows['total'],$page,$limit);
}else{$prev_link=null;$next_link=null;}

//IF ORDERBY NOT SET, SET DEFAULT
if(!isset($_GET['orderby']) OR trim($_GET['orderby']) == ""){
	//GET FIRST FIELD IN TABLE TO BE DEFAULT SORT
	$sql = "SELECT * FROM `".$config['table']."` LIMIT 1";
	$result = mysql_query($sql) or die(mysql_error());
	$array = mysql_fetch_assoc($result);
	//first field
	$i = 0;
	foreach($array as $key=>$value){
		if($i > 0){break;}else{
		$orderby=$key;}
		$i++;		
	}
	//default sort
	$sort="ASC";
}else{
	$orderby=mysql_real_escape_string($_GET['orderby']);
}	

//IF SORT NOT SET OR VALID, SET DEFAULT
if(!isset($_GET['sort']) OR ($_GET['sort'] != "ASC" AND $_GET['sort'] != "DESC")){
	//default sort
		$sort="ASC";
	}else{	
		$sort=mysql_real_escape_string($_GET['sort']);
}

//GET DATA
$sql = "SELECT * FROM `".$config['table']."` ORDER BY $orderby $sort LIMIT $startrow,$limit";
$result = mysql_query($sql) or die(mysql_error());

//START TABLE AND TABLE HEADER
echo "<table id='userlist' class='table table-striped table-bordered table-condensed open-sans'>\n<thead>\n<tr>";
$field1 = columnSortArrows('id','ID',$orderby,$sort);
$field2 = columnSortArrows('username','Username',$orderby,$sort);
$field3 = columnSortArrows('registration_ip','Last IP',$orderby,$sort);
$field4 = columnSortArrows('email','Email',$orderby,$sort);
$field5 = columnSortArrows('registered','Registered',$orderby,$sort);
$field6 = columnSortArrows('last_visit','Last Visit',$orderby,$sort);
echo "<th style='text-align:center'>" . $field1 . "</th>\n";
echo "<th style='text-align:center'>" . $field2 . "</th>\n";
echo "<th style='text-align:center'>" . $field3 . "</th>\n";
echo "<th style='text-align:center'>" . $field4 . "</th>\n";
echo "<th style='text-align:center'>" . $field5 . "</th>\n";
echo "<th style='text-align:center'>" . $field6 . "</th>\n";
echo "<th style='text-align:center'>SharerLink</th>\n";
echo "<th style='text-align:center'>Actions</th>\n";
echo "</tr>\n</thead>\n";

//reset result pointer
mysql_data_seek($result,0);

//LOOP TABLE ROWS
echo "<tbody>\n";
while($row = mysql_fetch_assoc($result)){
  
  $userID = $row['id'];
  $userIP = $row['registration_ip'];
  if ($userID != 1) {
  
	echo "<tr id='row-".$userID."'>\n";
	echo "<td style='text-align:center'>".$row['id']."</td>\n";
	
	$q_totalShout = mysql_query("SELECT id FROM ip_shouts WHERE user_id='$userID'") or die(mysql_error());
	$totalShout = mysql_num_rows($q_totalShout);
	if ($totalShout == 0) { $cLabel = 'label-important'; }
	else if ($totalShout < 10) { $cLabel = 'label-warning'; }
	else { $cLabel = 'label-success'; }
	echo "<td><span class='label ".$cLabel." tip-top' title='Total Shout'>".$totalShout."</span> <a href='profile.php?id=".$userID."' target='_blank' class='tip-top' title='User Profile'>".stripslashes(rtrim($row['username']))."</a></td>\n";
	
	echo "<td>".$row['registration_ip']."</td>\n";
	echo "<td>".$row['email']."</td>\n";
	echo "<td style='text-align:center'>".date('d-m-Y g:i A', $row['registered'])."</td>\n";
	echo "<td style='text-align:center'>".date('d-m-Y g:i A', $row['last_visit'])."</td>\n";
	$callSharerlink = mysql_query("SELECT * FROM ip_sharerlinks WHERE user_id='$userID'") or die(mysql_error());
	$showSL = mysql_fetch_assoc($callSharerlink);
	$checkSharerlink = mysql_num_rows($callSharerlink);
	echo "<td id='usersh-".$userID."'>";
	if ($checkSharerlink == 1) { echo '<a href="'.$showSL['sharerurl'].'" target="_blank">'.stripslashes(rtrim($showSL['sharername'])).'</a>'; }
	echo "</td>";
	
	echo "<td style='text-align:center'>";
	echo '<div id="action-'.$userID.'" class="btn-group">';
  echo '<a href="../forum/profile.php?action=delete_user&id='.$userID.'" target="_blank" class="btn btn-mini tip-top btn-danger" title="Eliminate User from Database"><i class="icon-trash"></i></a>';
  echo '<a href="../forum/admin/bans.php?sort_by=1&add_ban='.$userID.'" target="_blank" class="btn btn-mini tip-top btn-inverse" title="Ban User from Entering IsharePortal"><i class="icon-ban-circle"></i></a>';
  echo '</div>';
  
	echo "</td>";
	echo "</tr>\n";
	
	}
	
}
echo "</tbody>\n";

//END TABLE
echo "</table>\n";

if(!($prev_link==null && $next_link==null && $pagination_links==null)){
echo '<div class="pagination"><ul>'."\n";
echo $prev_link;
echo $pagination_links;
echo $next_link;
echo "</ul></div>\n";
}

/*FUNCTIONS*/

function columnSortArrows($field,$text,$currentfield=null,$currentsort=null){	
	//defaults all field links to SORT ASC
	//if field link is current ORDERBY then make arrow and opposite current SORT
	
	$sortquery = "sort=ASC";
	$orderquery = "orderby=".$field;
	
	if($currentsort == "ASC"){
		$sortquery = "sort=DESC";
		$sortarrow = '<i class="icon-chevron-up"></i>';
	}
	
	if($currentsort == "DESC"){
		$sortquery = "sort=ASC";
		$sortarrow = '<i class="icon-chevron-down"></i>';
	}
	
	if($currentfield == $field){
		$orderquery = "orderby=".$field;
	}else{	
		$sortarrow = null;
	}
	
	return '<a href="?'.$orderquery.'&'.$sortquery.'">'.$text.'</a> '. $sortarrow;	
	
}

?>
    
</div><!-- /.row-fluid -->

</div><!-- /.container -->
    
<?php html_footer(); ?>

<?php html_script(); ?>
<script>
function eliminate_user(uid) {
alert(uid);
/*
$.ajax({
  var postData = 'action=eliminate&uid='+uid;
  type: 'POST',
  url: 'admin_actions.php',
  data: postData,
  success: function (response) {
    if (response == 'OK') {
      $("tr#row-"+uid).remove().fadeOut();
    } else {
      alert('ERROR: '+response);
    }
  }
});
*/
}
function ban_user(uid) {
$.ajax({
  var uip = $(this).data('ip');
  var postData = 'action=ban&uid='+uid+'&uip='+uip;
  type: 'POST',
  url: 'admin_actions.php',
  data: postData,
  success: function (response) {
    if (response == 'OK') {
      $("span#btnAction-"+uid).html('<button class="btn btn-mini tip-top btn-success" title="Unban User" onlick="unban_user('+uid+')"><i class="icon-circle-blank"></i></button>');
    } else {
      alert('ERROR: '+response);
    }
  }
});
}
function unban_user(uid) {
$.ajax({
  var postData = 'action=unban&uid='+uid;
  type: 'POST',
  url: 'admin_actions.php',
  data: postData,
  success: function (response) {
    if (response == 'OK') {
      $("span#btnAction-"+uid).html('<button class="btn btn-mini tip-top btn-inverse" title="Ban User from Entering IsharePortal" onlick="ban_user('+uid+')"><i class="icon-ban-circle"></i></button>');
    } else {
      alert('ERROR: '+response);
    }
  }
});
}
function remove_sharerlink(uid) {
$.ajax({
  var postData = 'action=remove&uid='+uid;
  type: 'POST',
  url: 'admin_actions.php',
  data: postData,
  success: function (response) {
    if (response == 'OK') {
      $("td#usersh-"+uid).html('');
      $(this).remove().fadeOut();
    } else {
      alert('ERROR: '+response);
    }
  }
});
}
var checkUserSession = setInterval(checkSession, 5000);
function checkSession(){
  $.ajax({
    type: 'GET', url: 'session.php?check=1&i=' + Math.random(),
    success: function(data){
      if (data == 'NotLoggedIn') { window.location.replace('login.php'); }
    }
  });
}
</script>

<?php echo html('end'); ?>
