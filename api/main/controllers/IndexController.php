<?php
class IndexController implements IController {
	protected $data;
	protected $model;
	protected $viewForAjax;
	protected $params;
	protected $format;
	
	/* start IndexController */
	public function __construct() {
			$fc=Router::getInstance();
			$this->params=$fc->getParams();	
			$this->format=$fc->getFormat();
			$this->makeObj();
			$this->auth->checklogin();
	}
	
	/* Creation model and view objects */
	protected function makeObj() {
		$this->data=new ClearData();
		$this->model=new IndexModel();
		$this->viewForAjax=new ViewForAjax();
		$this->auth=new Auth();			
	}
	
	/* Page by default */
	public function indexAction () {	
		echo "index page!";
	}	
	
	public function postAuthAction() {
		if($this->auth->checklogin()) {
			$msg = array('success'=> 1, 'msg'=> 'all very well');
		} else {
			$msg = array('success'=> 0, 'msg'=> 'OPPSS');
		}
		return $this->viewForAjax->switchFormat($msg, $this->format);	
	}
	
	public function _getResponse() {
		$resp = array(); 
		if($_SERVER['REQUEST_METHOD'] == 'PUT') { 
			$putdata = file_get_contents('php://input'); 
			$exploded = explode('&', $putdata);  

			foreach($exploded as $pair) { 
				$item = explode('=', $pair); 
				if(count($item) == 2) { 
					$resp[urldecode($item[0])] = urldecode($item[1]); 
				} 
			} 
			return $resp;
		}	elseif($_SERVER['REQUEST_METHOD'] == 'DELETE') { 
			$putdata = file_get_contents('php://input'); 
			$exploded = explode('&', $putdata);  

			foreach($exploded as $pair) { 
				$item = explode('=', $pair); 
				if(count($item) == 2) { 
					$resp[urldecode($item[0])] = urldecode($item[1]); 
				} 
			} 
			return $resp;
		}
	}		
	
	public function getFilesAction() {
		$name  = '';
		if(!empty($_GET['name'])){
			$name = $_GET['name'];
		}
		$msg=$this->model->getFiles($name);
		if(!$msg){
			$result = array();
			$result['success']= 0;
			$result['msg']= 'Empty response';
		} else {
			$result = array();
			$result['success']= 1;
			$result['result']= $msg;
		}
		return $this->viewForAjax->switchFormat($result	, $this->format);			
	}
	
	public function postFilesAction() {
		$msg = $this->model->updateFiles($_POST);
		if(!$msg){
			$result = array();
			$result['success']= 0;
			$result['msg']= 'File/Folder is not updated';
		} else {
			$result = array();
			$result['success']= 1;
			$result['msg']= $msg;
		}
		
		return $this->viewForAjax->switchFormat($result, $this->format);	
	}
		
	public function putFilesAction() {
		$put = $this->_getResponse();
		$msg = $this->model->createFiles($put);
		if(!$msg){
			$result = array();
			$result['success']= 0;
			$result['msg']= 'File/Folder is not created';
		} else {
			$result = array();
			$result['success']= 1;
			$result['msg']= $msg;
		}
		return $this->viewForAjax->switchFormat($result, $this->format);	
	}		
	
	public function deleteFilesAction() {
		$delete = $this->_getResponse();
		$msg=$this->model->deleteFiles($delete);
		if(!$msg){
			$result = array();
			$result['success']= 0;
			$result['msg']= 'File/Folder is not deleted';
		} else {
			$result = array();
			$result['success']= 1;
			$result['msg']= $msg;
		}
		return $this->viewForAjax->switchFormat($result, $this->format);	
	}	
}