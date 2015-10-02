<?php
class Session {
	/* Singleton session  */
	static $_instance;
	public static function getInstance() {
		if(!(self::$_instance instanceOf self))
		self::$_instance=new self();
		return self::$_instance;
	}

	private function __construct() {
		session_start();
	}

}