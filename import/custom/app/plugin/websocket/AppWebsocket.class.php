<?php
class AppWebsocket extends Websocket{
	protected $service;
	protected $current_socket;
	private $num = 0;
	public function beforeSend($socket, $data){
		$this->current_socket = $socket;
		$r = $this->import($data);
		if($r === false){
			$this->printJson(false, 'file is invalid');
		}else{
			$this->printJson(false, 'import successfully');
		}
	}
	public function import($file, $n = false){
		if(!file_exists($file)){
			return false;
		}
		$this->service = S('import');
		$list = array();
		if(is_file($file)){
			$this->readFile($file, $n);
		}else{
			$list = FileComponent::readDir($file, true, 'csv');
			foreach ($list as $v){
				$t = $this->readFile($file.DS.$v, $n);
			}
		}
		$this->printJson(false, 'success, total '.$this->num.' records!');
		return true;
	}
	
	private function readFile($file, $n = false){
		if(!file_exists($file) || !is_file($file)){
			return false;
		}
		$list = array();
		$handle = fopen($file, 'r');
		$j = 0;
		while(!feof($handle) && ($n === false || (is_numeric($n) && $j <= $n))){
			$t = fgets($handle, 4096);
			if($j != 0){
				$r = $this->service->insertDB($t);
				if($r !== false){
					$info = $this->filter($r);
					$this->num++;
					if($this->num % 100 == 0){
						$this->printJson(false, 'successfully '.$this->num.' records!');
					}
// 					$this->printJson(true, $info);
// 					$list[] = $r;
				}
			}
			$j++;
		}
		fclose($handle);
		return $list;
	}
	
	private function filter($data){
		$demo = Configure::custom('fields');
		$res = array();
		foreach ($demo as $key => $value){
			$res[$key] = isset($data[$key]) ? $data[$key] : '';
		}
		return $res;
	}
	
	protected function printJson($status, $data){
		$this->send($this->current_socket, json_encode(array('status' => $status, 'data' => $data)));
	}
}