<?php
Includes::useComponent('SUtil');
class AjaxController extends Controller{
	public $autoLayout = false;
	public $autoView = false;
	public function upload(){
		$file = SUtilComponent::upload($_FILES, 'imgFile');
		if($file == false){
			echo json_encode(array('error' => 1, 'message' => L(array('file', 'ds', 'upload', 'ds', 'failed'))));
		}else{
			echo json_encode(array('error' => 0, 'url' => $file));
		}
	}
}