<?php

if (!defined('SITE_ROOT')) {	define('SITE_ROOT', './'); }
require_once(SITE_ROOT.'include/html.php'); // HTML structures

// Load top level HTML structures
html('start');
html_meta();
html_css();
html_favicon();
html_jquery();
html('body');

?>

<div id="loginText" style="position:fixed;width:1000px;top:20%;left:50%;margin-left:-500px;text-align:center;color:#333;">
<h1 class="open-sans" style="font-size:200px;line-height:200px;"><span style="color:#c00">Error</span> 404</h1>
<a href="index.php"><img id="loginLogo" src="assets/img/logo.png"></a>
</div>

<?php html_script(); ?>

<?php echo html('end'); ?>
