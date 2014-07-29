<?php
class CategoryService extends AppService{
	public $model = 'category';
	
	public function getParts($check_show = true, $parent_id = 0, $level = 1){
		$conditions = array();
		if($parent_id !== false){
			if(!is_numeric($parent_id)){
				$parent_id = 0;
			}
			$conditions = array('parent_id' => $parent_id);
		}
		if($check_show){
			$conditions['is_main_show'] = 1;
		}
		$list = D('category')->find($conditions, '*', 'order_asc', '', false, false);
		if(is_numeric($level) && $level > 1){
			foreach ($list as $key => $value){
				$list[$key]['direct_cates'] = $this->getParts($check_show,$value['id'], $level - 1);
			}
		}
		return $list;
	}
	
	public function getParentDirectPart($id){
		if(empty($id)){
			return false;
		}
		$info = D('category')->findById($id);
		if($info == false){
			return false;
		}else{
			$result = array();
			if($info['parent_id'] != 0){
				$result['parent_id'] = $info['parent_id'];
				$result['cate_id'] = $info['id'];
			}else{
				$result['parent_id'] = $info['id'];
				$tmp = $this->getParts(false, $id);
				if(isset($tmp[0]) && isset($tmp[0]['id'])){
					$result['cate_id'] = $tmp[0]['id'];
				}else{
					$result['cate_id'] = -1;
				}
			}
			return $result;
		}
	}
}