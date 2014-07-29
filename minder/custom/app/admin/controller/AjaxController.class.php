<?php
Includes::useComponent('SUtil');
class AjaxController extends Controller{
	public $autoLayout = false;
	public $autoView = false;
	public function upload(){
		$file = SUtilComponent::upload($_FILES, 'imgFile');
		if($file == false){
			echo json_encode(array('error' => 1, 'message' => '文件上传失败!'));
		}else{
			echo json_encode(array('error' => 0, 'url' => $file));
		}
	}
}