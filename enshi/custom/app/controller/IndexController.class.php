<?php
class IndexController extends AppController{
	public function index(){
		$service = S('article');
		$dongtai = $service->getArticles(7, '', 8);
		
		$tongzhi = $service->getArticles(22, '', 4);
		$zhaoshang = $service->getArticles(13, '', 6);
		$enshi = $service->getArticles(25, '', 11);
		$lvyou = $service->getArticles(21, '', 4);
		$juanzeng = $service->getArticles(14, '', 5);
		
		$photoSer = S('photo');
		$meitu = $photoSer->getArticles(23, '', 6);
		$jianji = $photoSer->getArticles(24, '', 6);
		
		$about = S('category')->getParts(false, 1, 1);
		
		$info = D('category')->find(array('is_index_recom' => 1), 'id', 'updated DESC','', 1);
		$condition = array("thumbnail <> ''");
		if(isset($info[0], $info[0]['id'])){
			$condition = array('cate_id' => $info[0]['id']);
		}
		$list = D('article')->find($condition, array('id', 'title', 'thumbnail'), 'updated DESC', '', 4);
		$this->assign('photos', $list);
		
		$this->assign('dongtai', $dongtai['list']);
		$this->assign('tongzhi', $tongzhi['list']);
		$this->assign('zhaoshang', $zhaoshang['list']);
		$this->assign('enshi', $enshi['list']);
		$this->assign('lvyou', $lvyou['list']);
		$this->assign('juanzeng', $juanzeng['list']);
		$this->assign('meitu', $meitu['list']);
		$this->assign('jianji', $jianji['list']);
		$this->assign('about', $about);
	}
}