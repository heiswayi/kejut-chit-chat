<?php

if (!defined('FORUM_ROOT')) {	define('FORUM_ROOT', '../forum/'); }
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

<div id="wrap">
<div class="navbar navbar-top"><div class="navbar-inner"><div class="container">
<a class="brand" href="index.php"><div class="logo-ip"></div></a>         
</div></div></div>

<div class="container">
    
<div class="row-fluid">
      
<div class="span8" style="margin:0 auto;float:none">
   
<div id="global-content" style="padding:20px;border:1px solid #31b0d5;background:#fff;">
<div id="global-title"><i class="icon-comments-alt"></i> Kejut Chit-Chat!</div>
<div id="shoutbox" style="text-align:center;padding:100px;">
<h1 style="color:#dd4b39;font-size:100px;"><i class="icon-ban-circle"></i></h1>
<p style="font-size:20px;">Sorry, only users with IP <span style="color:#31b0d5;"><i class="icon-laptop"></i> 10.122.*.*</span> are allowed to access this page! :(</p>
<a href="../forum" class="btn btn-large btn-info">Back to Chit-Chat Forum</a>
</div><!-- /#shoutbox -->
</div>
    
</div><!-- /.row-fluid -->

</div><!-- /.container -->
    
<?php html_footer(); ?>

<?php html_script(); ?>

<?php echo html('end'); ?>
