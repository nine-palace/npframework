<?php
class CategoryController extends AppController{
	public function filter($data){
		$params = array(
				'name' => array('name' => '栏目名称'),
				'parent_id' => array('required' => false),
				'is_main_show' => array('required' => false),
				'is_index_show' => array('required' => false),
				'is_index_recom' => array('required' => false),
				'link_url' => array('name' => '外部链接', 'type' => 'url', 'required' => false),
				'order_asc' => array('required' => false)
				
		);
		$result = ValidateComponent::validate($data['post'], $params);
		if($result === false){
			$this->showMsg(ValidateComponent::get_msg(),false);
			return false;
		}
		$conditions = array('name' => $result['name']);
		$conditions['parent_id'] = isset($result['parent_id']) ? $result['parent_id'] : 0;
		if(!empty($this->id)){
			$conditions[] = "id <> '{$this->id}'";
		}
		$count = D('category')->count($conditions);
		if($count != 0){
			$this->showMsg('栏目名称已存在', false);
			return false;
		}
		
		$result['created'] = time();
		return $result;
	}
	
	public function afterModify(){
		$parts = S('category')->getParts(false, 0, 1);
		$this->assign('parts', $parts);
	}
}