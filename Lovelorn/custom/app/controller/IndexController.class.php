<?php
class IndexController extends AppController{
	public function index(){
		$service = S('article');
		$article = $service->getArticles(1, '', 1);
		$content = isset($article['list'][0]) ? $article['list'][0]['content'] : '';
		$this->assign('introduction', $content);

		$photos = S('photo')->getArticles(2, '', 4);
		$this->assign('photos', $photos['list']);
	}
}