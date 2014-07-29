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
		$menus = array(
				'article' => '新闻管理',
				'product' => '产品管理',
				'photo' => '图片管理',
				'category' => '栏目管理'
		);
		$this->assign('system_site_name', Configure::getCustom('system_site_name'));
		$this->assign('menus', $menus);
		$this->assign('domain', __DOMAIN__.'admin/');
		
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
		switch ($this->model){
			case 'category' : 
				$this->current['action'] = $this->view == 'index' ? '栏目列表' : (empty($this->id) ? '添加栏目' : '编辑栏目');
				$this->current['title'] = $this->view == 'index' ? '栏目列表' : (empty($this->id) ? '添加栏目' : '编辑栏目');
				break;
			case 'article' : 
				$this->current['action'] = $this->view == 'index' ? '新闻列表' : (empty($this->id) ? '添加新闻' : '编辑新闻');
				$this->current['title'] = $this->view == 'index' ? '新闻列表' : (empty($this->id) ? '添加新闻' : '编辑新闻');
				break;
			case 'product' : 
				$this->current['action'] = $this->view == 'index' ? '产品列表' : (empty($this->id) ? '添加产品' : '编辑产品');
				$this->current['title'] = $this->view == 'index' ? '产品列表' : (empty($this->id) ? '添加产品' : '编辑产品');
				break;
			case 'photo' : 
				$this->current['action'] = $this->view == 'index' ? '图片列表' : (empty($this->id) ? '添加图片' : '编辑图片');
				$this->current['title'] = $this->view == 'index' ? '图片列表' : (empty($this->id) ? '添加图片' : '编辑图片');
				break;
			default:;
		}
		$this->assign('current', $this->current);
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
			$conditions[] = in_array($this->model, array('product', 'photo')) ? "name like '%{$this->request->params['get']['name']}'%" : "title like '%{$this->request->params['get']['title']}%'";
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
		foreach ($res['list'] as $key => $value){
			if($value[$cate_key] == 0){
				$res['list'][$key]['cate_name'] = '顶级栏目';
			}else{
				$info = D('category')->findById($value[$cate_key], 'name');
				$res['list'][$key]['cate_name'] = isset($info['name']) ? $info['name'] : '';
			}
		}
		$this->assign('list', $res['list']);
		
		if($res['count'] > 0){
			$pages = $this->pages($res['count'], $limit, $page);
			$this->assign('pages', $pages);
		}
	}
	
	public function modify(){
		if(!empty($this->request->params['post'])){
			$data = $this->filter($this->request->params);
			if($data == false){
				$info = array_merge($this->request->params['post'], array('id' => $this->id));
				$this->assign('info', $info);
			}else{
				if(!empty($this->id)){
					$data['updated'] = time();
					$res = D($this->model)->update(array('id' => $this->id), $data);
					$info = array_merge($data, array('id' => $this->id));
					$this->assign('info', $info);
					if($res == true){
						$this->showMsg('更新成功');
					}else{
						$this->showMsg('更新失败', false);
					}
				}else{
					$data['created'] = time();
					$data['updated'] = time();
					$res = D($this->model)->add($data);
					if($res == true){
						$this->showMsg('添加成功');
					}else{
						$this->showMsg('添加失败', false);
					}
				}
			}
		}else{
			if(!empty($this->id)){
				$info = D($this->model)->findById($this->id);
				if(empty($info)){
					$this->showMsg('目标不存在', false);
				}else{
					$this->assign('info', $info);
				}
			}
		}
		$this->afterModify();
	}
	
	public function afterModify(){
		
	}
	
	public function delete(){
		$result = true;
		if(empty($this->id)){
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
				$msg = '删除失败!';
			}else{
				$msg = '删除成功!';
			}
			$_SESSION['return_message'] = array('status' => $result, 'msg' => $msg);
		}
		echo json_encode($result);
		$this->autoLayout = false;
		$this->autoView = false;
	}
	
	public function filter($data){
		return $data;
	}
	
	public function showMsg($msg = '', $boolean = true){
		$this->assign('return_message', array('status' => $boolean, 'msg' => $msg));
	}
	
	public function pages($count, $limit, $currentPage){
		$out = '<div class="left">第&nbsp';
		$start = 1;
		$start += ($currentPage - 1) * $limit;
		$end = $currentPage * $limit;
		$total = (int)($count / $limit);
		if(($count % $limit) > 0){
			$total++;
		}
		$out .= $start.'&nbsp-&nbsp'.$end.'&nbsp/&nbsp'.$count.'&nbsp条</div>';
		$uri = $_SERVER['REQUEST_URI'];
		$t = explode('?', $uri);
		$page = $this->request->http_protol.$this->request->http_host.$t[0].'?';
		$prev = $currentPage - 1;
		$next = $currentPage + 1;
		if(isset($t[1])){
			$tmp = explode('&', $t[1]);
			foreach ($tmp as $key => $value){
				if(!empty($value)){
					$tt = explode('=', $value);
					if(!isset($tt[0]) || $tt[0] != 'page'){
						$page .= $value.'&';
					}
				}
			}
		}
		$page .= 'page=';
		$out .= '<div class="right">';
		if($prev > 0){
			$out .= '<a href="'.$page.$prev.'">上一页</a>';
		}
		if($total < 6){
			for ($i = 1; $i <= $total; $i++){
				$out .= $currentPage == $i ? "<span>{$i}</span>" : '<a href="'.$page.$i.'">'.$i.'</a>';
			}
		}else{
			if($currentPage < 4){
				for ($i = 1; $i < 5; $i++){
					$out .= $currentPage == $i ? "<span>{$i}</span>" : '<a href="'.$page.$i.'">'.$i.'</a>';
				}
			}else{
				$out .= '<a href="'.$page.'page=1">1</a>';
				$out .= '<span>...</span>';
			}
			if($currentPage > $total - 3){
				for ($i = $total - 3;$i <= $total; $i++){
					$out .= $currentPage == $i ? "<span>{$i}</span>" : '<a href="'.$page.'page='.$i.'">'.$i.'</a>';
				}
			}else{
				$out .= '<a href="'.$page.$prev.'">'.$prev.'</a>';
				$out .= "<span>{$currentPage}</span>";
				$out .= '<a href="'.$page.$next.'">'.$next.'</a>';
				$out .= '<span>...</span>';
				$out .= '<a href="'.$page.$total.'">'.$total.'</a>';
			}
		}
		if($next <= $total){
			$out .= '<a href="'.$page.$next.'">下一页</a>';
		}
		$out .= "</div>";
		return $out;
	}
	
	public function isLogin(){
// 		return true;
		if(!isset($_SESSION['admin_user_name'])){
			return false;
		}else{
			$this->current['admin_user'] = $_SESSION['admin_user_name'];
			return true;
		}
	}
}