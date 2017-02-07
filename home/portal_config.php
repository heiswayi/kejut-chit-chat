<?php

// Setup database for IsharePortal
if (!defined('DB_SERVER')) {	define('DB_SERVER', 'localhost'); }
if (!defined('DB_USERNAME')) {	define('DB_USERNAME', 'root'); }
if (!defined('DB_PASSWORD')) {	define('DB_PASSWORD', 'toor'); }
if (!defined('DB_NAME')) {	define('DB_NAME', 'chitchat'); }

// Setup site
if (!defined('BASE_URL')) {	define('BASE_URL', 'http://localhost:8080/chit-chat/home/'); }
if (!defined('KEEP_SHOUTS')) {	define('KEEP_SHOUTS', 30); } // default 30 days

?>