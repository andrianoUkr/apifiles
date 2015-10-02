<?php
class ClearData {

//Cleaer Array
	public function clearArray ($data) {
		if(is_array($data)) {
			foreach ($data as $key=>$value) {
				if(!is_array($value)) {
					$data[$key] = trim(strip_tags($value));
				} else {
					$this->clearArray($value);
				}
			}
		}
		if(!is_array($data)) {
				$data = trim(strip_tags($data));
			}
		return $data;
	}

//Cleaer Email
	public function clearEmail ($data) {
		if(!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", $data)) {	
			return $data="";
		}
		return $data;
	}
	
//Clear int values
	public function clearInt($data) {
		return $data = abs((int)$data);
	}
}