<?php
class Controller extends Object{
	public $app = '';
	public $autoLayout = true;
	public $autoLayoutFromStyleTemplate = true;
	public $autoView = true;
	public $name = '';
	public $view = '';
	public $params = '';
	public $request = '';
	public $layout = 'default';
	public $template = '';
	public $refuseAccessActions = array();
	protected $current = array();
	private $assignParams = array();
	private $defauleRefuseAccessActions = array(
			'beforeFilter', 'afterFilter', 'beforeAction', 'beforeRender', 'defineConst',
			'filter', 'initialize', 'isLicitAction', 'redirect', 'render', 'setRequest');
	
	public function __construct(){
	}
	/**
	 * it will excute before object create,
	 * you can override this method.
	 */
	public function initialize(){
		
	}
	/**
	 * it will excute before excute action
	 * you can override it
	 */
	public function beforeFilter(){
		
	}
	/**
	 * it will excute before rander
	 * you can override it
	 */
	public function beforeRender(){
		
	}
	
	/**
	 * you can override it
	 * @param unknown $data
	 * @return unknown
	 */
	public function filter($data){
		return $data;
	}
	/**
	 * it will excute after rander but before output to client
	 * you can override it
	 */
	public function afterFilter(){
	
	}
	
	/**
	 * output json data
	 * @param unknown $data
	 */
	public function printJson($data) {
// 		$jcb = SUtil::getStr($_REQUEST['jsoncallback']);
		$jcb = false;
		if ($jcb) {//如果是跨域操作
			echo $jcb . "(" . json_encode($data) . ");";
		} else {
			echo json_encode($data);
		}
		exit();
	}
	/**
	 * define some const for css , js, image ... files' request in client 
	 */
	public function defineConst(){
		$root = $this->request->http_protol.$this->request->http_host.DS;
		if(!defined('CUSTOM')){ define('CUSTOM', 'custom'.DS);}
		$root .= CUSTOM;
		if(!defined('__APP__')){
			$url = $root.Configure::getCustomDirectory('app').DS;
			if(!empty($this->app)){
				$url .= $this->app.DS;
			}
			define('__APP__', $url);
		}
		if(!defined('__STATIC__')){
			define('__STATIC__', __APP__.Configure::getCustomDirectory('static').DS);
		}
		if(!defined('__PUBLIC__')){
			$url = empty(Configure::$publicFileDirectory) ? Configure::getCustomDirectory('view').DS : Configure::$publicFileDirectory.DS;
			define('__PUBLIC__', $root.$url);
		}
	}
	
	final function render(){
// 		$this->defineConst();
		$this->beforeRender();
		if($this->autoView === true){
			Includes::useView('');
			Includes::useView('App');
			$view = new AppView();
			$view->setData($this->assignParams);
			Configure::$autoLayout = $this->autoLayout;
			Configure::$autoLayoutFromStyleTemplate = $this->autoLayoutFromStyleTemplate;
			$view->display($this->view, $this->layout, $this->name, $this->app, $this->template);
		}
	}
	
	final function assign($key, $value){
		$this->assignParams[$key] = $value;
	}
	/**
	 * judge whether the action can access from http request, if not, return false 
	 * @param string $action
	 * @return boolean
	 */
	final function isLicitAccess($action){
		if(empty($action)){
			return false;
		}
		$actions = array_merge($this->defauleRefuseAccessActions, $this->refuseAccessActions);
		if(in_array($action, $actions)){
			return false;
		}else{
			return true;
		}
	}
	/**
	 * direct to a new url
	 * @param mixed $url
	 */
	final function redirect($url){
		header("Location:{$url}");
		exit;
		if(!is_array($url)){
			$url = ROOT_URL.$url;
		}else{
		}
	}
	
	final function setRequest($request){
		$this->request = $request;
	}
	
	final function end($status = 0){
		exit($status);
	}
	final function setTemplate($name, $view){
		if(empty($this->name)){
			$this->name = $name;
		}
		if(empty($this->view)){
			$this->view = $view;
		}
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
?>