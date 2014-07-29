<?php
class AccountController extends AppController{
	public function index(){
		$conditions = array();
		if(isset($this->request->params['get']['title'])){
			$conditions[] = $this->model == 'article' ? "title like '%{$this->request->params['get']['title']}'%" : "name like '%{$this->request->params['get']['title']}%'";
			$this->assign('search_title', $this->request->params['get']['title']);
		}
		if(isset($this->request->params['get']['page']) && is_numeric($this->request->params['get']['page'])){
			$this->page = $this->request->params['get']['page'] > 1 ? $this->request->params['get']['page'] : 1;
		}
		$conditions[] = "level < {$this->current['admin_user_level']}";
		$order = 'updated DESC';
		$res = S($this->model)->getList($conditions, '', $order, '', $this->limit, $this->page);
		$list = $this->beforeIndexShow($res['list']);
		$this->assign('list', $list);
		if($res['count'] > 0){
			$pages = $this->pages($res['count'], $this->limit, $this->page);
			$this->assign('pages', $this->pages);
		}
		$this->current['action'] = 'list';
	}
	public function filter($data){
		$params = array(
				'name' => array('prefix' => 'account', 'required' => true),
				'level' => array('type' => 'int', 'value' => 0),
				'purview'
		);
		$result = ValidateComponent::validate($data['post'], $params);
		if($result === false){
			$this->showMsg(ValidateComponent::get_msg(),false);
			return false;
		}
		$count = D('account')->count(array('name' => $result['name']));
		if($count > 0){
			$this->showMsg(L(array('account', 'ds', 'name', 'ds', 'exists')), false);
			return false;
		}
		if($result['level'] >= $this->current['admin_user_level']){
			$this->showMsg(L(array('cannot', 'ds', 'add', 'ds', 'account')), false);
			return false;
		}
		$pas = Configure::getCustom('default_password') == null ? '123456' : Configure::getCustom('default_password');
		$result['password'] = md5($pas);
		$list = Configure::$purview;
		foreach ($list as $key => $value){
			$list[$key] = in_array($key, $result['purview']) ? true : false;
		}
		Configure::setCustom('temp_purview', $list);
		unset($result['purview']);
		return $result;
	}
	public function beforeIndexShow($list){
		$indexs = array(
				'name' => array('type' => 'normal'),
				'updated' => array('type' => 'date'),
				'created' => array('type' => 'date')
		);
		$this->assign('index_list', $indexs);
		return $list;
	}
	public function afterModify($info, $flag = null){
		$indexs = array(
				'name' => array('type' => 'name'),
				'purview' => array('type' => 'checkbox', 'values' => 'purviews')
		);
		$this->assign('index_list', $indexs);
		if(Configure::getCustom('temp_after_modify') === true || Configure::getCustom('temp_after_add') === true){
			S('purview')->addPurview($info['name'], Configure::getCustom('temp_purview'));
		}
		$this->assign('purviews', Configure::$purview);
		$purview = array();
		if(isset($info['name'])){
			$tmp = D('purview')->find(array('user_name' => $info['name']), 'pur_name,pur_value');
			foreach ($tmp as $v){
				$purview[$v['pur_name']] = $v['pur_value'];
			}
		}
		$info['purview'] = $purview; 
		return $info;
	}
}