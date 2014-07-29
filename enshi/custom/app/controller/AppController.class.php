<?php
Includes::useComponent('SUtil');
class AppController extends Controller{
	public $layout = 'enshi';
	public function beforeRender(){
		parent::beforeRender();
		$main_menus = S('category')->getParts(true, 0, 2);
		$this->assign('main_menus', $main_menus);
		$this->assign('default_recom_image', __PUBLIC__.'images/default_recom.jpg');
		$this->assign('default_article_image', __PUBLIC__.'images/default_article.jpg');
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
}