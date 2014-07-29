<?php
Includes::useComponent('Validate');
class IndexController extends AppController{
	public function index(){
		$service = S('article');
		$article = $service->getCategoryList(1, '', 1);
		$content = isset($article['list'][0]) ? $article['list'][0] : '';
		$tmp = ValidateComponent::validate($content, array('content' => array('type' => 'string', 'length' => false)));
		$content['content'] = isset($tmp['content']) ? SUtilComponent::substr($tmp['content'], 200) : $content['content'];
		$article['info'] = $content;
		$this->assign('introduction', $article);
		$lists = array(
				array('id' => 2, 'type' => 'list', 'limit' => 10),
				array('id' => 7, 'type' => 'photo', 'limit' => 4),
				array('id' => 18, 'type' => 'list', 'limit' => 10),
				array('id' => 8, 'type' => 'list', 'limit' => 10),
				array('id' => 4, 'type' => 'photo', 'limit' => 4),
				array('id' => 23, 'type' => 'list', 'limit' => 10)
		);
		$res = array();
		foreach ($lists as $v){
			if(!isset($v['id'])){ continue;}
			$id = $v['id'];
			$type = isset($v['type']) ? $v['type'] : 'list_time';
			$limit = isset($v['limit']) ? $v['limit'] : 10;
			$info = $service->getCategoryList($id, '', $limit);
			$res[] = array('type' => $type, 'info' => $info);
		}
		$this->assign('information', $res);
		
		$tops = $service->getArticlesThumbnail('', true, 4);
		$this->assign('tops', $tops['list']);
		$this->view = 'main';
	}
}