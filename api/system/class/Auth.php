<?php
class Auth {
	public function checklogin () {
		if(!empty($_SESSION['auth'])) {
			return  true;
		} elseif(!empty($_SERVER['HTTP_AUTH'])) {	
			$auth_hash = base64_decode($_SERVER['HTTP_AUTH']);
			$hash = md5(md5($auth_hash.'random!@#'));
			$auth  = split(":", $auth_hash);
			if(ADMIN_PASSWORD == $hash  and ADMIN_LOGIN == $auth[0] ){
				$url =  $_SERVER['HTTP_HOST'];
				$arr = array();
				$arr['login'] = $auth[0]; 
				$arr['access'] = md5(ADMIN_HASH);
				$value = json_encode($arr);
				setcookie("WebAuth", $value, time()+3600, "/", $url);						
				$_SESSION['auth'] = $auth_hash;
				return true;
			} else{
				return false;
			}
		} elseif (!empty($_SERVER['PHP_AUTH_USER']) and !empty($_SERVER['PHP_AUTH_PW']) ) {
			$auth_hash = $_SERVER['PHP_AUTH_USER'].':'. $_SERVER['PHP_AUTH_PW'];
			$hash = md5(md5($auth_hash.'random!@#'));			
			if(ADMIN_PASSWORD == $hash  and ADMIN_LOGIN == $_SERVER['PHP_AUTH_USER']) {
				$_SESSION['auth'] = $auth_hash;
				return true;
			}	
		} elseif(!empty($_GET['auth']) and $_GET['auth'] == 'login') {
			header('WWW-Authenticate: Basic realm="Secure zone"');
			header('HTTP/1.0 401 Unauthorized');
			die('Error Authenticate Basic');
		
		} else {
			die('Error Auth');
		}
	}
}