<?php
class CategoryService extends AppService{
	public $model = 'category';
	
	public function getParts($check_show = true, $parent_id = 0, $level = 1, $content_type = false){
		$list = array();
		if(is_numeric($level) && $level > 1){
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
			if($content_type !== false){
				if(!is_array($content_type)){ $content_type = array($content_type);}
				$type = '';
				foreach ($content_type as $t){
					$type .= ",'{$t}'";
				}
				$type = substr($type, 1);
				$conditions[] = "content_type in({$type})";
			}
			$list = D('category')->find($conditions, '*', 'order_asc', '', false, false);
			foreach ($list as $key => $value){
				$list[$key]['direct_cates'] = $this->getParts($check_show,$value['id'], $level - 1);
			}
		}
		return $list;
	}
	public function getDirects($cate_id, $keywords){
		$directs = array();
		if(!empty($keywords)){
			$directs[] = array('id' => -1, 'name' => '关键字搜索  '.$keywords);
		}else{
			$cate = D('category')->findById($cate_id, array('id', 'name', 'parent_id'));
			if($cate !== false){
				$parent_id = $cate['parent_id'];
				$directs = array_merge($this->getDirects($parent_id, $keywords), array($cate));
			}
		}
		return $directs;
	}
	
	public function getDirectParts($id){
		$list = D('category')->find(array('parent_id' => $id), array('id', 'name'), array('order_asc DESC', 'updated DESC'));
		return is_array($list) ? $list : array();
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