<?php
class NavigationService extends AppService{
	public function getNavigations($parent_id = false, $module = false, $purview = false, $degree = 2){
		$result = array();
		if($degree > 0){
			
		}
	}
	
	public function getNavigationsList($parent_id = false, $module = false, $purview = false, $start_up_time = false, $end_up_time = false, $start_ct_time = false, $end_ct_time = false){
		$conditions = array();
		is_numeric($parent_id) ? $conditions['parent_id'] = $parent_id : '';
		is_numeric($module) ? $conditions['module'] = $module : '';
		is_numeric($purview) ? $conditions[] = 'purview <= '.$purview : '';
		is_numeric($start_up_time) ? $conditions[] = 'updated >= '.$start_up_time : '';
		is_numeric($end_up_time) ? $conditions[] = 'updated <= '.$end_up_time : '';
		is_numeric($start_ct_time) ? $conditions[] = 'created >= '.$start_ct_time : '';
		is_numeric($end_ct_time) ? $conditions[] = 'created <= '.$end_ct_time : '';
		return $this->getList($conditions);
	}
}