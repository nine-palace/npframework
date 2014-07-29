<?php
Includes::useComponent('File');
Class IndexController extends AppController{
	
	public function index(){
		$this->assign('address', Configure::custom('websocket_host'));
		$this->assign('port', Configure::custom('websocket_port'));
		$this->assign('fields', Configure::custom('fields'));
	}
	
	public function import(){
		$dir = isset($this->params['get']['dir']) ? urldecode($this->params['get']['dir']) : '';
		$file = 'f:\kuaipan/file/5000.csv';
		$res = $this->readFile($file);
		var_dump($res);
		$this->autoView = false;
	}
	
	private function readFile($file){
		if(!file_exists($file) || !is_file($file)){
			return false;
		}
		$list = array();
		$handle = fopen($file, 'r');
		while(!feof($handle)){
			$list[] = fgets($handle, 4096);
		}
		fclose($handle);
		return $list;
	}
	
	private function insertDB($record){
		$t = explode(',', $record);
		$demo = array('name','cardNo','descriot','ctfTp','ctfId','gender','birthday','address',
				'zip','dirty','district1','district2','district3','district4','district5','district6','firstNm',
				'lastNm','duty','mobile','tel','fax','email','nation','taste','education','company',
				'ctel','caddress','czip','family','version','id'
			);
		$row = array();
		$length = count($demo);
		for($i = 0; $i < $length; $i++){
			if(isset($t[$i])){
				$row[$demo[$i]] = $t[$i];
			}
		}
		$res = D('record')->add($row);
		$this->afterInsert($res);
		return $res;
	}
	private function afterInsert($record){
		
	}
}