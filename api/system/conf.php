<?php
//Set charset
header('Content-Type: text/html; charset=utf-8'); //Charset utf-8

//Set database variables
define("DB_HOST", "localhost");//Host of database
define("DB_NAME", "name");//name of database
define("DB_USER", "root");//Name of database user
define("DB_PASS", "");//User password	
define("AU_ERROR", "Error request!!!");//User password	

$dir = dirname($_SERVER['PHP_SELF']).'/';
$count_dir = count(explode('/', trim($dir,'/')));
$url = dirname($_SERVER['SCRIPT_FILENAME']).'/';
$system_dir = dirname($url).'/system/';

define("DIR", $dir);//directory
define("COUNT_DIR", $count_dir);
define("SYSTEM_DIR", $system_dir);//url to system
define("CURR_DIR", $url);//path site
define("INDEX", "index.php");


define('ADMIN_LOGIN','admin'); //Administrator login
define('ADMIN_PASSWORD','dca59b799661644d264487d8cfefbf1d'); //Administrator password
define('ADMIN_HASH','655few15jklk8u8j1dfd32ucv98mluj1l7'); //Administrator hash