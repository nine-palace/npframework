<?php
class ArticleController extends AppController{
	public function filter($data){
		$params = array(
				'title' => array('prefix' => 'category'),
				'cate_id' => array('type' => 'int', 'value' => 0),
				'content' => array('type' => 'html'),
				'thumbnail_delete' => array('type' => 'int', 'value' => 0),
				'order_asc' => array('type' => 'int', 'value' => 10)
		
		);
		$result = ValidateComponent::validate($data['post'], $params);
		if($result === false){
			$this->showMsg(ValidateComponent::get_msg(),false);
			return false;
		}
		if($result['thumbnail_delete'] === 1){
			$result['thumbnail'] = '';
		}else{
			if(isset($_FILES['thumbnail']) && !empty($_FILES['thumbnail']['name'])){
				$file = SUtilComponent::upload($_FILES, 'thumbnail', $result['title']);
				if($file === false){
					$this->showMsg(L(array('file', 'ds', 'upload', 'ds', 'failed')), false);
					return false;
				}
				$result['thumbnail'] = $file;
			}
		}
		unset($result['thumbnail_delete']);
		return $result;
	}
	
	public function beforeIndexShow($list){
		$indexs = array(
				'title' => array('type' => 'normal'),
				'cate_id' => array('type' => 'choose', 'values' => 'parts'),
				'thumbnail' => array('type' => 'thumbnail'),
				'order_asc' => array('type' => 'normal', 'values' => 10)
		);
		$this->assign('index_list', $indexs);
		return $list;
	}
	public function afterModify($info, $flag = null){
		$indexs = array(
				'title' => array('type' => 'name'),
				'cate_id' => array('type' => 'choose', 'values' => 'parts'),
				'thumbnail' => array('type' => 'thumbnail'),
				'content' => array('type' => 'text'),
				'order_asc' => array('type' => 'normal', 'values' => 10)
		);
		$this->assign('index_list', $indexs);
		return $info;
	}
}