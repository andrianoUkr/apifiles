<?php 

class Db{
	private static $instance = null;
	private function __construct(){}
	private function __clone(){}
	static function getInstanse(){
		if(self::$instance==null){
			self::$instance=new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, 
			array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			self::$instance->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); 
		}
	return self::$instance;
	}
}