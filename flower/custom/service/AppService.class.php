<?php
class AppService extends Service{
	public $model = '';
	public function __construct($model){
		parent::__construct($model);
		if(empty($this->model)){
			$this->model = $model;
		}
	}
	public function getList($conditions = array(), $fields = array(), $order = array(), $group = array(), $limit = 10, $page = 1){
		$result = array();
		$model = D($this->model);
		$result['list'] = $model->find($conditions, $fields, $order, $group, $limit, $page);
		$result['count'] = $model->count($conditions);
		return $result;
	}
	public function getArticles($cate_id = '', $keywords = '', $limit = 10, $page = 1){
		if(!empty($keywords)){
			$this->model = 'article';
			$result = $this->getList(array("title like '%{$keywords}' or content like '%{$keywords}%'"), '', array("order_asc DESC", "updated DESC"), $limit, $page);
		}else if(!empty($cate_id)){
			$cates = D('category')->find(array("id='{$cate_id}' or parent_id = '{$cate_id}'"), 'group_concat(id) as cate_id');
			$result = array('list' => array(), 'count' => 0);
			if(!empty($cates) && isset($cates[0]['cate_id'])){
				$result = $this->getList(array("cate_id in ({$cates[0]['cate_id']})"), '', array("order_asc DESC", "updated DESC"), '', $limit, $page);
			}
		}else{
			$result = $this->getList('', '', array("order_asc DESC", "updated DESC"), $limit, $page);
		}
		return $result;
	}
	
	public function getArticlesThumbnail($cate_id = '', $recom = false, $limit = 10, $page = 1){
		$cates = '';
		if(!empty($cate_id)){
			$cates = D('category')->find(array("id='{$cate_id}' or parent_id = '{$cate_id}'"), 'group_concat(id) as cate_id');
			
		}elseif($recom == true){
			$cates = D('category')->find(array("is_index_recom" => 1), 'group_concat(id) as cate_id');
		}
		$result = array('list' => array(), 'count' => 0);
		if(!empty($cates) && isset($cates[0]['cate_id'])){
			$result = $this->getList(array("cate_id in ({$cates[0]['cate_id']})", "thumbnail != ''", "thumbnail is not null"), '', array("order_asc DESC", "updated DESC"), '', $limit, $page);
		}
		return $result;
	}
	
	public function getCategoryList($cate_id, $keywords = '', $limit = 10, $page = 1){
		$articles = $this->getArticles($cate_id, $keywords, $limit, $page);
		$info = D('category')->findById($cate_id, array('id', 'name'));
		if(!is_array($info)){
			return array();
		}else{
			$info = $this->singleLanguageFilter($info, 'name', false);
		}
		$info['list'] = $articles['list'];
		return $info;
	}
}