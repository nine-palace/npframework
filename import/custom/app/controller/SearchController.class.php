<?php
class SearchController extends AppController{
	
	public function index(){
		$name = isset($_GET['name']) ? $_GET['name'] : '';
		$gender = isset($_GET['gender']) ? $_GET['gender'] : '';
		$address = isset($_GET['address']) ? $_GET['address'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$page = is_numeric($page) && $page > 1 ? $page : 1; 
		$condition = array();
		$limit = 35;
		if(!empty($condition)){
			$condition[] = "name like '%{$name}%'";
		}
		if(!empty($address)){
			$condition[] = "address like '%{$address}%'";
		}
		if(!empty($gender)){
			$upper = strtoupper($gender);
			$lower = strtolower($gender);
			$condition[] = "(gender = '{$upper}' or gender = '{$lower}')";
		}
		$list = D('record')->find($condition, '', '', '', $limit, $page);
		$count = D('record')->count($condition);
		$pages = $this->pages($count, $limit, $page);
		$this->assign('list', $list);
		$this->assign('pages', $pages);
		$this->assign('name', $name);
		$this->assign('gender', $gender);
		$this->assign('address', $address);
	}
}