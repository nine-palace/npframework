<?php
class ArticleController extends AppController{
	
	public function initialize(){
		parent::initialize();
		$parts = S('category')->getParts(false, false, 1, array('news', 'introduction'));
		$this->assign('parts', $parts);
	}
	
	public function filter($data){
		$params = array(
				'title' => array('name' => '新闻标题'),
				'cate_id' => array('required' => false),
				'summary',
				'content' => array('name' => '新闻内容', 'length' => false),
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
		return $result;
	}
}