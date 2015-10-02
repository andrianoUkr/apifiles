<?php
class ORMModel{
	protected $db;
	protected $data;
	protected $resdata;
	protected $msg="";
	
	protected static $_instance = null;	
	private static $_table = null;
	
	protected $_limit = '';
	protected $_where ='';
	protected $_and_where = '';	
	protected $_arbitrary = '';
	protected $_val=array();		

	public function __construct(){
		$this->db=Db::getInstanse();
		$this->data=new ClearData();
	}
	
	public function quote($value){
		return $this->db->quote($value);
	}

	/*
	*select query start 
	*@param string: $sql - text of query
	*return array: query result or  ERROR-message
	*/
	public function getQueryDB($sql){
		try{
			$res=$this->db->query($sql);
			while($result = $res->fetch(PDO::FETCH_ASSOC)){
				$data[]=$result;
			}
			if (!empty($data) and  is_array($data) and count($data) > 1 ){
				return $this->resData=$data;
			}
			elseif(!empty($data) and is_array($data) and count($data) == 1){
				return $data[0];
			}
			elseif(empty($data)){
				return $data = false;
			}
			else{
				return $this->msg=AU_ERROR;
			}
		}
		catch(PDOException $e){
			return $e->getMessage();
		}			
	}
	/*
	* запрос работает на добавление, удаление и обновление
	*/
	public function setQueryDB($sql){
		try{
			$res=$this->db->exec($sql);
			if(empty($res)){
				return $this->msg=AU_ERROR;
			}
			else{
				return $res;
			}
		}
		catch(PDOException $e){
			$this->error_code=$e->getCode();
			if($e->getCode() == 23000 ){
				return $this->msg ='repetition!';
			}
			else{
				return $this->msg=$e->getMessage();
			}
		}	
	}
	
	/* THIS TABLE*/
	public static function for_table($table=NULL){
		if($table != null){
			self::$_table = $table;
			$args=func_get_args();
			$cnt=count($args);
			if(!empty($args[1])){
				self::$_table=$args[0].' AS '.$args[1];
			}
			if($cnt > 2){
				$listTable='';
				for ($i=2; $i < $cnt; $i++) {
					if($i%2==0){
						if($args[$i+1] == ''){
							$alias = $args[$i+1]='';
						}
						else{
							$alias =' AS '.$args[$i+1];
						}
						$listTable.= ", ".$args[$i].$alias;
					}
				}
				self::$_table.=$listTable;
			}			
		}		
		if(self::$_instance==null){
			self::$_instance = new self; 
		}
		return self::$_instance;		
	}	
	
	/* WHERE */
	public function where($field='', $symbol='', $value=''){
		if(!empty($field) and !empty($symbol) and isset($value)){
			$field= "`".$field."`";
			$value=$this->data->clearArray($value);
			if(isset($value)){
				$value="'".$value."'";
				$this->_where= "WHERE ".$field.$symbol.$value;
			}
		}
		return self::for_table();
	}
	
	/* AND WHERE */
	public function and_where($field, $symbol, $value){
		$field= "`".$field."`";
		$value=$this->data->clearArray($value);
		if(isset($value)){
			$value="'".$value."'";
			if(!empty($this->_and_where)){

				$this->_and_where .= " AND ".$field.$symbol.$value;
			}
			else{
				$this->_and_where= " AND ".$field.$symbol.$value;
			}
		}
		return self::for_table();
	}

	/* ARBITRARY */
	public function arbitrary($arbitrary=''){
		$this->_arbitrary=$arbitrary;
		return self::for_table();
	}	
	
	/* LIMIT */
	public function limit($first='1', $second=''){
		if(!empty($second)){
			$second=", ".$second;
		}
		$this->_limit="LIMIT ".$first.$second;
		return self::for_table();
	}	
	
	/* SELECT */
	public function selectDB ($table=''){
		$fields='';
		$where='';
		$and_where='';
		$arbitrary='';
		$limit='';
		
		if(empty($table)){
			$table= self::$_table;
			self::$_table='';
		}
		if(!empty($this->_val)){
			$fields = implode(", ", array_keys($this->_val));
			$this->_val='';
		}
		else{
			$fields= "*";
		}
		
		if(!empty($this->_where)){
			$where=$this->_where;
			$this->_where='';
		}
		if(!empty($this->_and_where)){
			$and_where=$this->_and_where;
			$this->_and_where='';
		}
		if(!empty($this->_arbitrary)){
			$arbitrary=$this->_arbitrary;
			$this->_arbitrary='';
		}
		if(!empty($this->_limit)){
			$limit=$this->_limit;
			$this->_limit='';
		}
		
		$sql="SELECT $fields FROM $table $where $and_where $arbitrary $limit";	
		$row = $this->getQueryDB($sql);
		return $row;
	}
	
	/* UPDATE */	
	public function updateDB ($table=''){
		$where='';
		$fields='';
		$values='';		
		if(empty($table)){
			$table= self::$_table;
			self::$_table='';
		}
		if(!empty($this->_where)){
			$where=$this->_where;
			$this->_where='';
		}	
		if(!empty($this->_val)){
			$fields = implode(", ", array_keys($this->_val));
			$values = implode(", ", array_values($this->_val));			
			$this->_val='';
		}		
	
		$sql="UPDATE $table SET $fields=$values $where";			
		$row=$this->setQueryDB($sql);
		return $row;
	}


	/* DELETE */
	public function deleteDB ($table=''){
		$where='';
		if(empty($table)){
			$table= self::$_table;
			self::$_table='';
		}
		if(!empty($this->_where)){
			$where=$this->_where;
			$this->_where='';
		}
		if(!empty($where)){
			$sql="DELETE FROM $table $where";		
			$row=$this->setQueryDB($sql);
			return $row;		
		}
	}

	/* INSERT */
	public function insertDB ($table=''){
		$fields='';
		$values='';
		if(empty($table)){
			$table= self::$_table;
			self::$_table='';
		}
		if(!empty($this->_val)){
			$fields = implode(", ", array_keys($this->_val));
			$values = implode(", ", array_values($this->_val));			
			$this->_val='';
		}
		$sql = "INSERT INTO $table ($fields) VALUES ($values)";		
		$row = $this->setQueryDB($sql);
		return $row;
	}	
	
	
	/* FIELD AND VALUES*/	
	public function __set($name, $value) {
		$name= "`".$name."`";
		$value=$this->data->clearArray($value);
		if(!empty($value)){
			$value="'".$value."'";
		}
        return $this->_val[$name]= $value;
    }	
	
	public function __get($name){
        return $this->_val[$name];
    }

	public function __call($name, $arguments){
		$argument='';
		$name= $name;
		$pattern = '/(__)/';
		if(preg_match($pattern,$name)){
			$replacement='.';
			$name=preg_replace($pattern, $replacement, $name, 1);
		}
		
		$arguments=$this->data->clearArray($arguments);
		if(!empty($arguments)){
			if(!empty($arguments[1])){
				$argument="'".$arguments[1]."'";
			}
			if(!empty($arguments[0])){
				$name=$name.' AS '.$arguments[0];
			}
		}
		else{
			$argument="'error field'";
		}
        $this->_val[$name]= $argument;
		return self::for_table();
    }
}