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
}