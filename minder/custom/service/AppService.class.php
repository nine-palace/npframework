<?php
class AppService extends Service{
	public $model = 'app';
	public function getList($conditions = array(), $fields = array(), $order = array(), $group = array(), $limit = 10, $page = 1){
		$result = array();
		$model = D($this->model);
		$result['list'] = $model->find($conditions, $fields, $order, $group, $limit, $page);
		$result['count'] = $model->count($conditions);
		return $result;
	}
}