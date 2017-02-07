<?php
function sanitize($string) {
  $clean = stripslashes(rtrim(htmlspecialchars($string)));
  return $clean;
}

// Convert plain URL to clickable link
/*
function clickable($ret){
  $ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a class=\"label label-info link-tip\" title=\"\\2\" href=\"\\2\" target=\"_blank\"><i class=\"icon-link semi-transparent\"></i> Link</a>", $ret);
  $ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a class=\"label label-info link-tip\" title=\"\\2\" href=\"http://\\2\" target=\"_blank\"><i class=\"icon-link semi-transparent\"></i> Link</a>", $ret);
  $ret = preg_replace("/@(\w+)/", "<span class=\"mention\" onclick=\"insertNickname('\\1');\">@\\1</span>", $ret);
  //$ret = preg_replace("/[#](\w+)/", "<a href=\"hashtag.php?q=\\1\" id=\"hashtag\" data-q=\"\\1\">#\\1</a>", $ret);
  return $ret;
}
*/

// URL, #hashtag, @mention
function clickable($input){
$input = preg_replace(
array('/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/', 
'/(^|[^a-z0-9_])@([a-z0-9_]+)/i', 
'/(^|[^a-z0-9_])#([a-z0-9_]+)/i'), 
array('<a href="$1" target="_blank" rel="nofollow" class="label label-info link-tip"><i class="icon-link semi-transparent"></i> Link</a>', 
'$1<span class="mention" onClick="insertNickname(\'$2\')">@$2</span>', 
'$1<a href="hashtag.php?q=$2" id="hashtag">#$2</a>'), 
$input);
return $input;
}

// Convert text to emoticons
function bbCode($var) {
  $search = array(  
    '/\{\:(.*?)\:\}/is',
    '/\[\:(.*?)\:\]/is',
    '/\[\;(.*?)\;\]/is',
    '/\{\;(.*?)\;\}/is',
    '/\[rt\](.*?)\[\/rt\]/is',
    '/\[quote\](.*?)\[\/quote\]/is',
    '/\[purple\](.*?)\[\/purple\]/is',
    '/\[blue\](.*?)\[\/blue\]/is',
    '/\[teal\](.*?)\[\/teal\]/is',
    '/\[green\](.*?)\[\/green\]/is',
    '/\[orange\](.*?)\[\/orange\]/is',
    '/\[pink\](.*?)\[\/pink\]/is',
    '/\[red\](.*?)\[\/red\]/is',
  ); 

  $replace = array(
    '<img src="assets/img/onionclub/$1.gif">',
    '<img src="assets/img/tuzkiclub/$1.gif">',
    '<img src="assets/img/smileys/$1.png">',
    '<img src="assets/img/cutes/$1.gif">',
    '<span class="rtquote">$1</span>',
    '<div class="t-quote"><i class="icon-quote-left"></i> $1 <i class="icon-quote-right"></i></div>',
    '<span class="c-purple">$1</span>',
    '<span class="c-blue">$1</span>',
    '<span class="c-teal">$1</span>',
    '<span class="c-green">$1</span>',
    '<span class="c-orange">$1</span>',
    '<span class="c-pink">$1</span>',
    '<span class="c-red">$1</span>',
  ); 

  $var = preg_replace ($search, $replace, $var); 
  return $var; 
}

// Convert text to emoticons (reply form)
function replybbCode($var) {
  $search = '/\[\;(.*?)\;\]/is'; 
  $replace = '<img src="assets/img/smileys/$1.png">'; 
  $var = preg_replace ($search, $replace, $var); 
  return $var; 
}

// Convert timestamp to time ago
function timeAgo($timestamp) {
    $timestamp      = (int) $timestamp;
    $current_time   = time();
    $diff           = $current_time - $timestamp;
    $intervals      = array (
        'year' => 31556926, 'month' => 2629744, 'week' => 604800, 'day' => 86400, 'hour' => 3600, 'minute'=> 60
    );
    if ($diff == 0)
    {
        return 'just now';
    }    
    if ($diff < 60)
    {
        return $diff == 1 ? $diff . ' second ago' : $diff . ' seconds ago';
    }        
    if ($diff >= 60 && $diff < $intervals['hour'])
    {
        $diff = floor($diff/$intervals['minute']);
        return $diff == 1 ? $diff . ' minute ago' : $diff . ' minutes ago';
    }        
    if ($diff >= $intervals['hour'] && $diff < $intervals['day'])
    {
        $diff = floor($diff/$intervals['hour']);
        return $diff == 1 ? $diff . ' hour ago' : $diff . ' hours ago';
    }    
    if ($diff >= $intervals['day'] && $diff < $intervals['week'])
    {
        $diff = floor($diff/$intervals['day']);
        return $diff == 1 ? $diff . ' day ago' : $diff . ' days ago';
    }    
    if ($diff >= $intervals['week'] && $diff < $intervals['month'])
    {
        $diff = floor($diff/$intervals['week']);
        return $diff == 1 ? $diff . ' week ago' : $diff . ' weeks ago';
    }    
    if ($diff >= $intervals['month'] && $diff < $intervals['year'])
    {
        $diff = floor($diff/$intervals['month']);
        return $diff == 1 ? $diff . ' month ago' : $diff . ' months ago';
    }    
    if ($diff >= $intervals['year'])
    {
        $diff = floor($diff/$intervals['year']);
        return $diff == 1 ? $diff . ' year ago' : $diff . ' years ago';
    }
}


function validateSharername($str) {
    return preg_match('/^[a-zA-Z0-9_.\s]+$/', $str);
}
function validateSharerlink($url) {
    $regex = "((https?|ftp)\:\/\/)?"; // Scheme
    $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
    $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP
    $regex .= "(\:[0-9]{2,5})?"; // Port
    $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
    $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
    $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor
    return preg_match("/^$regex$/", $url);
}

function getUrlAddress(){
  $url = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
  //return $url .'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
  $dirOnly = dirname($_SERVER['REQUEST_URI']);
  return $url .'://'.$_SERVER['HTTP_HOST'].$dirOnly;
}

function filename($string, $force_lowercase = false, $anal = false) {
    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
                   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                   "—", "–", ",", "<", ".", ">", "/", "?");
    $clean = trim(str_replace($strip, "", strip_tags($string)));
    $clean = preg_replace('/\s+/', "-", $clean);
    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
    return ($force_lowercase) ?
        (function_exists('mb_strtolower')) ?
            mb_strtolower($clean, 'UTF-8') :
            strtolower($clean) :
        $clean;
}

// Generate a random key of length $len
function random_keyx($len, $readable = false, $hash = false)
{
	$key = '';

	if ($hash)
		$key = substr(sha1(uniqid(rand(), true)), 0, $len);
	else if ($readable)
	{
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

		for ($i = 0; $i < $len; ++$i)
			$key .= substr($chars, (mt_rand() % strlen($chars)), 1);
	}
	else
		for ($i = 0; $i < $len; ++$i)
			$key .= chr(mt_rand(33, 126));

	return $key;
}

function get_valid_url($url) {
 
    $regex = "((https?|ftp)\:\/\/)?"; // Scheme
    $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
    $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP
    $regex .= "(\:[0-9]{2,5})?"; // Port
    $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
    $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
    $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor
 
    return preg_match("/^$regex$/", $url);
 
}

?>