<?php
Includes::useComponent('SUtil');
class ArticleController extends AppController{
	
	public function filter($data){
		$params = array(
				'title' => array('name' => '文章标题'),
				'cate_id' => array('required' => false),
				'summary',
				'content' => array('name' => '文章内容', 'length' => false),
				'order_asc' => array('required' => false)
		
		);
		$result = ValidateComponent::validate($data['post'], $params);
		if($result === false){
			$this->showMsg(ValidateComponent::get_msg(),false);
			return false;
		}
		if(isset($_FILES['thumbnail']) && !empty($_FILES['thumbnail']['name'])){
			$file = SUtilComponent::upload($_FILES, 'thumbnail', $result['title']);
			if($file === false){
				$this->showMsg('文件上传错误!', false);
				return false;
			}
			$result['thumbnail'] = $file;
		}
		$result['created'] = time();
		return $result;
	}
	public function afterModify(){
		$parts = S('category')->getParts(false, false, 1);
		$this->assign('parts', $parts);
	}
}