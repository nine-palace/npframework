<?php
class PurviewService extends AppService{
	private $purModel;
	public function addPurview($name, $purview){
		$this->purModel = D($this->model);
		if(is_array($purview)){
			foreach ($purview as $key => $value){
				$res = $this->modifyPurview($name, $key, $value);
			}
		}
	}
	
	private function modifyPurview($name, $pur_name, $value){
		$count = $this->purModel->count(array('user_name' => $name, 'pur_name' => $pur_name));
		$value = $value == true ? 'true' : 'false';
		if($count > 0){
			$res = $this->purModel->update(array('user_name' => $name, 'pur_name' => $pur_name, 'updated' => time()), array('pur_value' => $value));
		}else{
			$res = $this->purModel->add(array('user_name' => $name, 'pur_name' => $pur_name, 'pur_value' => $value, 'updated' => time(), 'created' => time()));
		}
		return $res;
	}
}