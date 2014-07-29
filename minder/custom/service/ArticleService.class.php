<?php
class ArticleService extends AppService{
	public $model = 'article';
	
	public function getArticles($cate_id, $limit = 4){
		$model = D('article');
		$cates = D('category')->find(array("id='{$cate_id}' or parent_id = '{$cate_id}'"), 'group_concat(id) as cate_id');
		$result = array();
		if(!empty($cates) && isset($cates[0]['cate_id'])){
			$result = D('article')->find(array("cate_id in ({$cates[0]['cate_id']})"), '*', '', '', 4);
		}
		return $result;
	}
}