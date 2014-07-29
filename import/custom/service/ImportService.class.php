<?php
Includes::useComponent('File');
class ImportService extends AppService{
	
	public function import($file, $n = false){
		if(!file_exists($file)){
			return false;
		}
		$list = array();
		if(is_file($file)){
			$this->readFile($file, $n);
		}else{
			$list = FileComponent::readDir($file, true, 'csv');
			foreach ($list as $v){
				$this->readFile($file.DS.$v, $n);
			}
		}
	}
	
	private function readFile($file, $n = false){
		if(!file_exists($file) || !is_file($file)){
			return false;
		}
		$list = array();
		return false;
		$handle = fopen($file, 'r');
		$j = 0;
		while(!feof($handle) && ($n === false || (is_numeric($n) && $j <= $n))){
			$t = fgets($handle, 4096);
			if($j != 0){
				$r = $this->insertDB($t);
				if($r){
					$list[] = $t;
				}
			}
			$j++;
		}
		fclose($handle);
		return $list;
	}
	
	public function insertDB($record){
		$t = explode(',', $record);
		$demo = array('name','cardNo','descriot','ctfTp','ctfId','gender','birthday','address',
				'zip','dirty','district1','district2','district3','district4','district5','district6','firstNm',
				'lastNm','duty','mobile','tel','fax','email','nation','taste','education','company',
				'ctel','caddress','czip','family','version','exten_id'
		);
		$row = array();
		$length = count($demo);
		for($i = 0; $i < $length; $i++){
			if(isset($t[$i])){
				$row[$demo[$i]] = $t[$i];
			}
		}
		$res = D('record')->add($row);
		return $res == false ? false : $row;
	}
}