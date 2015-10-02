<?php
class ViewForAjax
{
	protected $resultXML;

	//View For Ajax
	public function  __construct()
	{}


	public function switchFormat($array, $format='json') {
		switch ($format) {
			case 'json':
				$this->formatJson($array);
				break;
			case 'xml':
				$this->formatXml($array);
				break;
			case 'html':
				$this->formatHtml($array);
				break;
			case 'txt':
				$this->formatTxt($array);
				break;	
			default:
				$this->formatJson($array);				
		}
		
	}
	
	public function formatJson ($array) {
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($array);
	}
	
	public function formatXml($array) {
		$this->resultXML.= '<?xml version="1.0" encoding="utf-8"?><root>';	
		foreach ($array as $row) {
			$this->resultXML.='<car>';
			foreach($row as $key=>$value)
			{
				$this->resultXML.='<'.$key.'>'.$value.'</'.$key.'>';
			}
			$this->resultXML.='</car>';
			
		} 
		$this->resultXML.= '</root>';
		header('Content-type: text/xml; charset=utf-8');		
		echo $this->resultXML;	
	}

	public function formatHtml($array) {
		header('Content-type: text/html; charset=utf-8');
		echo '<html><head><title>AUTOSHOP</title></head><body><pre>';
		print_r($array);		
		echo '</pre></body></html>';
	}
	
	public function formatTxt($array) {
		header('Content-type: text/html; charset=utf-8');
		echo '<pre>';
		print_r($array);		
		echo '</pre>';	
	}
}