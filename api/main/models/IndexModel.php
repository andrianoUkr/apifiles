<?php
class IndexModel{
	protected $db;
	protected $maxDepth = 1;
	protected $data;
	protected $ORM;	
	protected $resdata;
	protected $msg="";

	public function __construct(){
		$this->data=new ClearData();
		// $this->ORM=new ORMModel();		
	}

	public function getFiles($dirname= '') {
		$arr_folder = array();
		$count = 0;
		$folder = UPLOAD_DIR;		
		$folder.= DIRECTORY_SEPARATOR . $dirname;
		if (is_dir($folder)) {
			$dir = new RecursiveDirectoryIterator($folder);
			$iter = new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::SELF_FIRST);
			while($iter->valid()) {
				if(!$iter->isDot()) {
					if( $iter->getDepth() < $this->maxDepth ) {
						$item=array();
						$item['id']	= $count;
						$item['size'] = $iter->getSize();
						$item['type'] = $iter->getType();
						$item['ext'] = $iter->getExtension();
						$item['pathname'] = $iter->getSubPathName();
						$item['filename'] = $iter->getFilename();
						$item['parent'] = '/'.$dirname ;
						$item['Exec'] = $iter->isExecutable();
						$item['sub'] = '';
						$count++;
						
						$arr_folder [] = $item;
					}
				}
				$iter->next();
			}
			return $arr_folder;			
		}
		if (is_file($folder)) {
			$file = file_get_contents($folder);
			if(empty($file)){
				return ' ';
			}
			return $file;
		}
	}

	public function updateFiles($data) {
		$data = $this->data->clearArray($data);
		if(!empty($data['name']) ) {
			$path = UPLOAD_DIR;		
			$path.= DIRECTORY_SEPARATOR . $data['name'];
			if(is_file($path) and !empty($data['content'])) {
				if(file_put_contents($path, $data['content'])) {
					return "File is updated";
				}
			}
			if(is_dir($path) and !empty($data['newName'])) {
				$newPath = UPLOAD_DIR.DIRECTORY_SEPARATOR . $data['newName'];	
				if(!is_dir($newPath)){
					if(rename($path, $newPath)) {
						return "Folder is updated";
					} 
				}
			}
		}	
	}	
	
	public function createFiles($data){
		$data = $this->data->clearArray($data);
		$allow_ext = array('txt');
		if(!empty($data['name'])) {
			$path = UPLOAD_DIR;		
			$path.= DIRECTORY_SEPARATOR . $data['name'];
			$name = explode('.', $data['name']);
			if(!empty($name[1]) and in_array($name[1], $allow_ext)){
				if(!file_exists($path) and is_dir(dirname($path)) and !empty($data['content'])) {
					if(file_put_contents($path, $data['content'])) {
						return "File is created";
					} 
				} elseif(!file_exists($path) and is_dir(dirname($path))){
					fopen($path, "w+");
					return "File is created";
				}
			} elseif(empty($name[1])) {
				if(!file_exists($path) and is_dir(dirname($path))){
					mkdir($path);
					return "Folder is created";
				}
			}
		}
	}
	
	public function _removeDirectory($dir) {
		if ($objs = glob($dir."/*")) {
			foreach($objs as $obj) {
				is_dir($obj) ? removeDirectory($obj) : unlink($obj);
			}
		}
		rmdir($dir);
		return true;
	}
		
	public function deleteFiles($data){
		$data = $this->data->clearArray($data);
		if(!empty($data['name']) ) {	
			$path = UPLOAD_DIR;		
			$path.= DIRECTORY_SEPARATOR . $data['name'];
			if(is_file($path)){
				if(unlink($path)) {
					return "You delete file";
				} 
			}	
			if(is_dir($path)){
				if($this->_removeDirectory($path)) {
					return "You delete folder";
				} 
			}	
		}
	}
}

