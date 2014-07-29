<?php
class CategoryController extends AppController{
	private $types;
	
	public function initialize(){
		parent::initialize();
		$this->types = Configure::getCustom('content_types');
		$this->types = empty($this->types) ? array() : $this->types;
		$this->assign('types', $this->types);
	}
	
	public function filter($data){
		$params = array(
				'name' => array('prefix' => 'category'),
				'parent_id' => array('type' => 'int', 'value' => 0),
				'is_main_show' => array('type' => 'int', 'value' => 0),
				'is_index_recom' => array('type' => 'int', 'value' => 0),
				'link_url' => array('type' => 'url'),
				'order_asc' => array('type' => 'int', 'value' => 10)
				
		);
		$result = ValidateComponent::validate($data['post'], $params);
		if($result === false){
			$this->showMsg(ValidateComponent::get_msg(),false);
			return false;
		}
		$tmp = $this->multiLanguageFilter($result['name']);
		if($tmp == false){
			$this->showMsg(L(array('category', 'ds', 'name', 'ds', 'cannot_empty')), false);
			return false;
		}
		$result['name'] = $tmp;
		$result['content_type'] = isset($data['post']['content_type']) && isset($this->types[$data['post']['content_type']]) ? $data['post']['content_type'] : 'article';
		$conditions = array('name' => $result['name']);
		$conditions['parent_id'] = isset($result['parent_id']) ? $result['parent_id'] : 0;
		if(!empty($this->id)){
			$conditions[] = "id <> '{$this->id}'";
		}
		$count = D('category')->count($conditions);
		if($count != 0){
			$this->showMsg(L(array('category', 'ds', 'name', 'ds', 'exists')), false);
			return false;
		}
		return $result;
	}
	public function loadCategorys(){
		$level = Configure::getCustom('func_admin_category_two') === true ? 1 : 0;
		$parts = S('category')->getParts(false, 0, $level);
		$parts = $this->singleLanguageFilter($parts, 'name');
		$res = array();
		foreach ($parts as $key => $value){
			$res[strval($value['id'])] = $value['name'];
		}
		$this->parts = $res;
		$this->assign('parts', $res);
	}
	
	public function beforeIndexShow($list){
		$indexs = array(
				'name' => array('type' => 'normal'),
				'parent_id' => array('type' => 'choose', 'values' => 'parts'),
				'order_asc' => array('type' => 'normal'),
				'content_type' => array('type' => 'choose', 'values' => $this->types),
				'is_main_show' => array('type' => 'radio'),
				'is_index_recom' => array('type' => 'radio')
		);
		$this->assign('index_list', $indexs);
		if(!is_array($list)){
			return $list;
		}else{
			return $this->singleLanguageFilter($list, 'name', true);
		}
	}
	public function afterModify($info, $flag = null){
		$indexs = array(
				'name' => array('type' => 'multi'),
				'parent_id' => array('type' => 'choose', 'values' => 'parts'),
				'content_type' => array('type' => 'choose', 'values' => $this->types),
				'is_main_show' => array('type' => 'radio', 'values' => 1),
				'is_index_recom' => array('type' => 'radio', 'values' => 0),
				'link_url' => array('type' => 'normal', 'values' => 'http://'),
				'order_asc' => array('type' => 'normal', 'values' => 10)
		);
		$this->assign('index_list', $indexs);
		return $flag === true ? $this->singleLanguageFilter($info, 'name', false, false) : $info;
	}
}