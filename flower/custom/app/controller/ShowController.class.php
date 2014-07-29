<?php
class ShowController extends AppController{
	protected $id = '';
	protected $cate_id = '';
	
	public function initialize(){
		parent::initialize();
		if(isset($this->request->params['url'][0]) && is_numeric($this->request->params['url'][0])){
			$this->id = $this->request->params['url'][0];
		}
		if(isset($this->request->params['post']['id']) && !empty($this->request->params['post']['id'])){
			$this->id = $this->request->params['post']['id'];
		}
		if(isset($this->request->params['get']['id']) && !empty($this->request->params['get']['id'])){
			$this->id = $this->request->params['get']['id'];
		}
	}
	public function index(){
		$this->current['cate_id'] = $this->id;
		$this->current['keywords'] = isset($this->request->params['get']['keywords']) ? $this->request->params['get']['keywords'] : '';
		if(!empty($this->id)){
			$cate = D('category')->findById($this->id, 'content_type');
			$this->current['content_type'] = $cate === false ? 'article' : $cate['content_type'];
		}else{
			$this->current['content_type'] = 'article';
		}
		$limit = 10;
		$page = 1;
		if(isset($this->request->params['get']['page']) && is_numeric($this->request->params['get']['page'])){
			$page = $this->request->params['get']['page'] > 1 ? $this->request->params['get']['page'] : 1;
		}
		$model = 'article';
		$list = S($model)->getArticles($this->id, $this->current['keywords'], $limit, $page);
		$count = $list['count'];
		$list = $list['list'];
		if($this->current['content_type'] == 'introduction' && isset($list[0]['id'])){
			$this->redirect(__DOMAIN__.'show/detail.html?id='.$list[0]['id']);
		}
		if($this->current['content_type'] == 'photo'){
			Includes::useComponent('Validate');
			foreach ($list as $key => $value){
				$tmp = ValidateComponent::validate($value, array('content' => array('type' => 'string', 'length' => false)));
				$list[$key]['content'] = isset($tmp['content']) ? $tmp['content'] : '';
			}
		}
		if($count > 0){
			$pages = $this->pages($count, $limit, $page);
			$this->assign('pages', $pages);
		}
		$this->assign('list', $list);
	}
	
	public function detail(){
		$this->single('article');
	}
	
	public function beforeRender(){
		parent::beforeRender();
		$cate = D('category')->findById($this->current['cate_id'], 'name');
		$this->current['cate_name'] = $cate === false ? '' : $this->singleLanguageFilter($cate, 'name', false);
		$this->assign('current', $this->current);
		$this->view = 'detail';
	}
	
	protected function redirectIndex(){
		$this->redirect(__DOMAIN__);
	}
	
	private function single($model){
		$info = D($model)->findById($this->id);
		if($info == false){
			$this->redirectIndex();
		}
		$views = $info['views'] + 1;
		$this->current['cate_id'] = $info['cate_id'];
		$res = D($model)->update(array('id' => $this->id), array('views' => $views));
		$this->current['content_type'] = 'article';
		$this->assign('detail', $info);
	}
}