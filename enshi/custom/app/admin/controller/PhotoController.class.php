<?php
class PhotoController extends AppController{
	public function initialize(){
		parent::initialize();
		$parts = S('category')->getParts(false, false, 1, 'photo');
		$this->assign('parts', $parts);
	}
	public function filter($data){
		$params = array(
				'name' => array('name' => '图片名称', 'required' => false),
				'cate_id' => array('required' => false),
				'order_asc' => array('required' => false)
		
		);
		$result = ValidateComponent::validate($data['post'], $params);
		if($result === false){
			$this->showMsg(ValidateComponent::get_msg(),false);
			return false;
		}
		if(isset($_FILES['thumbnail']) && !empty($_FILES['thumbnail']['name'])){
			if(!isset($result['name']) || empty($result['name'])){
				$name = explode('.', $_FILES['thumbnail']['name']);
				$result['name'] = $name[0];
			}
			$file = SUtilComponent::upload($_FILES, 'thumbnail', $result['name']);
			if($file === false){
				$this->showMsg('文件上传错误!', false);
				return false;
			}
			$result['thumbnail'] = $file;
		}else{
			$this->showMsg('请上传图片文件!', false);
			return false;
		}
		return $result;
	}
}