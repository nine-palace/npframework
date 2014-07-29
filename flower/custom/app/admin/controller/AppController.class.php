<?php
Includes::useComponent('Validate');
Includes::useComponent('SUtil');
Class AppController extends Controller{
	public $layout = 'admin';
	public $model = '';
	protected $id = '';
	public $refuseAccessAction = array('isLogin', 'showMsg', 'pages', 'afterModify');
	public function initialize(){
		if(!$this->isLogin()){
			$this->redirect(__DOMAIN__.'admin/login.html');
		}
		if(empty($this->model)){
			$this->model = strtolower($this->name);
		}
		$this->loadMenus();
		$this->loadCategorys();
		$action = empty($this->id) ? 'add' : 'modify';
		$this->current['title'] = $this->view == 'index' ? L(array($this->model, 'ds', 'list')) : L(array($action, 'ds', $this->model));
		if(isset($this->request->params['url'][0]) && is_numeric($this->request->params['url'][0])){
			$this->id = $this->request->params['url'][0];
		}
		if(isset($this->request->params['post']['id']) && !empty($this->request->params['post']['id'])){
			$this->id = $this->request->params['post']['id'];
		}
		if(isset($this->request->params['get']['id']) && !empty($this->request->params['get']['id'])){
			$this->id = $this->request->params['get']['id'];
		}
		if(isset($_SESSION['return_message']) && isset($_SESSION['return_message']['status']) && isset($_SESSION['return_message']['msg'])){
			$this->showMsg($_SESSION['return_message']['msg'], $_SESSION['return_message']['status']);
			unset($_SESSION['return_message']);
		}
	}
	public function beforeRender(){
		if(!isset($this->current['module'])){
			$this->current['module'] = strtolower($this->name);
		}
		if(!isset($this->current['id'])){
			$this->current['id'] = $this->id;
		}
		$this->assign('current', $this->current);
	}
	
	public function loadCategorys(){
		$parts = S('category')->getParts(false,false);
		$parts = $this->singleLanguageFilter($parts, 'name');
		$res = array();
		foreach ($parts as $key => $value){
			$res[strval($value['id'])] = $value['name'];
		}
		$this->assign('parts', $res);
	}
	
	public function index(){
		$limit = 10;
		$page = 1;
		$cate_key = $this->model == 'category' ? 'parent_id' : 'cate_id';
		$conditions = array();
		if(!empty($this->id)){
			$conditions[$cate_key] = $this->id;
		}
		if(isset($this->request->params['get']['title'])){
			$conditions[] = $this->model == 'article' ? "title like '%{$this->request->params['get']['title']}'%" : "name like '%{$this->request->params['get']['title']}%'";
			$this->assign('search_title', $this->request->params['get']['title']);
		}
		if(isset($this->request->params['get']['page']) && is_numeric($this->request->params['get']['page'])){
			$page = $this->request->params['get']['page'] > 1 ? $this->request->params['get']['page'] : 1;
		}
		$order = 'updated DESC';
		if(isset($this->request->params['get']['order'])){
			switch($this->request->params['get']['order']){
				case 'order_asc' : $order = 'order_asc ASC,updated DESC';break;
				case 'order_desc' : $order = 'order_asc DESC,updated DESC';break;
				case 'time_asc' : $order = 'updated ASC';
				default : break;
			}
		}
		$res = S($this->model)->getList($conditions, '', $order, '', $limit, $page);
		$list = $this->beforeIndexShow($res['list']);
		$this->assign('list', $list);
		
		if($res['count'] > 0){
			$pages = $this->pages($res['count'], $limit, $page);
			$this->assign('pages', $pages);
		}
		$this->current['action'] = 'list';
	}
	
	public function modify(){
		$flag = false;
		$info = false;
		if(!empty($this->request->params['post'])){
			$data = $this->filter($this->request->params);
			if($data == false){
				$info = array_merge($this->request->params['post'], array('id' => $this->id));
				$this->assign('info', $info);
			}else{
				$flag = true;
				if(!empty($this->id)){
					$data['updated'] = time();
					$res = D($this->model)->update(array('id' => $this->id), $data);
					$info = array_merge($data, array('id' => $this->id));
					$this->assign('info', $info);
					if($res == true){
						$this->showMsg(L(array('update', 'ds', 'successful')));
					}else{
						$this->showMsg(L(array('update', 'ds', 'failed')), false);
					}
				}else{
					$data['created'] = time();
					$data['updated'] = time();
					if(Configure::getCustom("func_admin_{$this->model}_add") === false){
						$this->showMsg(L(array('update', 'ds', 'failed')), false);
					}else{
						$res = D($this->model)->add($data);
						if($res == true){
							$this->showMsg(L(array('add', 'ds', 'successful')));
						}else{
							$this->showMsg(L(array('add', 'ds', 'failed')), false);
						}
					}
				}
			}
		}else{
			if(!empty($this->id)){
				$info = D($this->model)->findById($this->id);
				if(empty($info)){
					$this->showMsg(L(array('object', 'ds', 'not_exists')), false);
				}else{
					$flag = true;
					$this->assign('info', $info);
				}
			}
		}
		$info = $this->afterModify($info, $flag);
		$this->assign('info', $info);
		$this->current['action'] = 'modify';
	}
	public function beforeIndexShow($list){
		return $list;
	}
	public function afterModify($info, $flag = null){
		return $info;
	}
	public function afterUpdated($info){
		return $info;
	}
	
	public function delete(){
		$result = true;
		if(empty($this->id)){
			$result = false;
		}else{
			$func = "func_admin_{$this->model}_delete";
			if(Configure::getCustom($func) === false){
				$result = false;
			}else{
				$tmp = explode(',', $this->id);
				$id = '';
				foreach($tmp as $t){
					if(!empty($t)){
						$id .= ','.$t;
					}
				}
				$id = substr($id, 1);
				$conditions = array("id in ({$id})");
				$res = D($this->model)->delete($conditions);
				if($res === false || $res == 0){
					$result = false;
					$msg = L(array('delete', 'ds', 'failed'));
				}else{
					$msg = L(array('delete', 'ds', 'successful'));
				}
				$_SESSION['return_message'] = array('status' => $result, 'msg' => $msg);
			}
		}
		echo json_encode($result);
		$this->autoLayout = false;
		$this->autoView = false;
	}
	
	public function showMsg($msg = '', $boolean = true){
		$this->assign('return_message', array('status' => $boolean, 'msg' => $msg));
	}
	
	public function isLogin(){
		if(!isset($_SESSION['admin_user_name'])){
			return false;
		}else{
			$this->current['admin_user'] = $_SESSION['admin_user_name'];
			$this->loadLimits();
			return true;
		}
	}
	private function loadMenus(){
		if(Configure::getCustom('func_admin_'.strtolower($this->name)) !== true){
			$this->redirect(__DOMAIN__.'admin');
		}
		$menus = array();
		$tmp = Configure::getCustom('func_admin_menus');
		$tmp = is_array($tmp) ? $tmp : array();
		foreach ($tmp as $value){
			if(Configure::getCustom('func_admin_'.$value) === true){
				$menus[] = $value;
			}
		}
		$this->assign('menus', $menus);
		$this->assign('domain', __DOMAIN__.'admin/');
	}
	
	private function loadLimits(){
		if($this->current['admin_user'] == 'administrator'){
			Configure::setCustom('func_admin_category_add', true);
			Configure::setCustom('func_admin_category_delete', true);
		}else{
			Configure::setCustom('func_admin_category_add', false);
			Configure::setCustom('func_admin_category_delete', false);
		}
	}
}