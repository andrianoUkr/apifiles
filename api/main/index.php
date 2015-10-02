<?php
//error_reporting(E_ALL | E_STRICT); 
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
// error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(0);

require_once '../system/conf.php'; //File with system settings
require_once 'curr_conf.php'; //File with current settings

//Path to load classes
function upload($class){
	$file=$class.'.php';
	if (file_exists(SYSTEM_DIR.'class/'.$file)){
		include SYSTEM_DIR.'class/'.$file;
	} elseif (file_exists(SYSTEM_DIR.'db/'.$file))	{
		include SYSTEM_DIR.'db/'.$file;
	} elseif (file_exists(CURR_DIR.'controllers/'.$file))	{
		include CURR_DIR.'controllers/'.$file;
	} elseif (file_exists(CURR_DIR.'models/'.$file)){
		include CURR_DIR.'models/'.$file;
	}
}
spl_autoload_register("upload");	
$front=Router::getInstance(); //Router function
$front->route();
