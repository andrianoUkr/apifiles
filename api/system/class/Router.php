<?php

class Router
{
	/* default template */
	protected $_controller ="Index"; // default controller
	protected $_action ="index"; 	 // default action of Controller
	protected $_params; //params  through a browser -keys and  values 
	protected $_format;
	static $_instance;
	
	/* Singleton controller */
	public static function getInstance()
	{
		if(!(self::$_instance instanceOf self))
		self::$_instance=new self();
		return self::$_instance;
	}

	private function __construct()
	{	
		$session=Session::getInstance(); //session start
		$request=$_SERVER['REQUEST_URI']; //request url
	
	/* Parse URL according to the number of folders */

		$url = explode('?', trim($request));
		if (!empty($url[1])) {
			$request=$url[0];
		}	
		
		$url = explode('.', $request);
		if (!empty($url[1])) {
			$this->_format =$url[1];
			$request=$url[0];
		}		

		$splits = explode('/',trim($request,'/'));
		$num=COUNT_DIR; $i=0;
		while($i<$num) {
			array_shift($splits);
			$i++;
		}
		
		/* Choice  Controller */
		if (!empty($splits[0])) {
			$this->_controller=ucFirst($splits[0]).'Controller';
		}
		else {
			$this->_controller='IndexController';
		}		
		
		/* Choice action for Controller */
		if (!empty($splits[1])) {
			if ($_SERVER['REQUEST_METHOD']== "GET") {
				$this->_action='get'.ucFirst($splits[1]).'Action';
			} elseif ($_SERVER['REQUEST_METHOD']== "POST") {
				$this->_action='post'.ucFirst($splits[1]).'Action';
			} elseif ($_SERVER['REQUEST_METHOD']== "PUT") {
				$this->_action='put'.ucFirst($splits[1]).'Action';
			} elseif ($_SERVER['REQUEST_METHOD']== "DELETE") {
				$this->_action='delete'.ucFirst($splits[1]).'Action';
			} else {
				$this->_action='get'.ucFirst($splits[1]).'Action';
			}	
		} else {
			$this->_action='indexAction';
		}
		
		/* input parameter -key and value*/
		if(!empty($splits[2]))
		{
			$keys = $values = array();
			for($i=2,$cnt=count($splits);$i<$cnt;$i++)
			{
				if($i%2==0)
				{
					$keys[]=$splits[$i];
				}
				else
				{
					$values[]=$splits[$i];
				}
			}
			//var_dump($key[1]);	
			if (count($keys) == count($values) and !empty($values))
			{
				$this->_params=array_combine($keys,$values);
			}
			else 
			{
				//echo "error";
				//header('location:'.ALL_URL.'/'.URL);
			}
		}
	// print_r($this->_params);
	}
	
	private function __clone()
	{
	}
	
	public function getFormat()
	{
		return $this->_format;
	}	
	
	public function getParams()
	{
		return $this->_params;
	}

	public function getController()
	{
		return $this->_controller;
	}	
	
	public function getAction()
	{
		return $this->_action;
	}
		
	/* Router*/
	public function route()
	{
		if(class_exists($this->getController()))
		{
			$rc = new ReflectionClass($this->getController());
			if($rc->implementsInterface('IController'))
			{
				if($rc->hasMethod($this->getAction()))
				{
					$controller=$rc->newInstance();
					$method=$rc->getMethod($this->getAction());
					$method->invoke($controller);
					// print_r($controller);
				}
				else
				{
					// header('location:'.ALL_URL.'/'.URL);
				throw new Exception('Wrong Action');
				}
			}
			else
			{
				// header('location:'.ALL_URL.'/'.URL);
			throw new Exception('Wrong Interface');
			}
		}
		else
		{
			//header('location:./index/index');
			echo "ERROR-PAGE";
			//header('location:'.ALL_URL.'/error/index.php');
			//header('location://'.$_SERVER['HTTP_HOST'].ALL_URL.'/'.'index/index');
			//throw new Exeption('Wrong Controller');
		}
		
	}
	
	
}
