<?php
class Dispatcher{
	private $module = 'controller';
	private $defaultApp = '';
	private $defaultController = '';
	private $defaultAction = '';
	
	public function dispatch(HttpRequest $request){
			try{
			$request = $this->analyzeApp($request);
			if(!Includes::isCustomAppExisted($request->app)){
				throw new MissAppException($request->app);
			}
			$this->initialize($request->app);
			$request = $this->analyzeController($request);
			if(Includes::isCustomClassExisted($request->controllerName, 'controller', $request->app) === false){
				throw new MissControllerException($request->controllerName, $request->app);
			}
				$request->controllerClass = $this->getControllerClass($request->controllerName);
				Includes::useController($request->controllerName, $request->app);
				$controller = $request->controllerClass;
				$object = new $controller;
				$request = $this->analyzeAction($object, $request);
				if(!$this->checkAction($object, $request->action)){
					throw new MissActionException($request->action, $controller, $request->app);
				}
				$object->setRequest($request);
				$action = $request->action;
				ob_start();
				$object->app = $request->app;
				$object->setTemplate($request->controllerName, $action);
				$object->params = $request->params;
				$object->defineConst();
				$object->initialize();
				$object->beforeFilter();
				$object->$action();
				$object->render();
				$object->afterFilter();
				$output = ob_get_clean();
				echo $output;
		}catch (Exception $e){
			$this->exception($e);
		}
	}
	/**
	 * analyze the app param
	 * @param HttpRequest $request
	 * @return HttpRequest
	 */
	private function analyzeApp(HttpRequest $request){
		if(empty($request->app)){
			$defaultApp = Configure::getCustomRouter('app');
			if(isset($request->params['url'][0]) && !empty($request->params['url'][0])){
				$app = $request->params['url'][0];
				if(Includes::isCustomAppExisted($app) === true){
					$request->app = $app;
					array_shift($request->params['url']);
				}else{
					$request->app = $defaultApp;
				}
			}else{
				$request->app = $defaultApp;
			}
		}
		return $request;
	}
	/**
	 * analyze the controller param
	 * @param HttpRequest $request
	 * @return HttpRequest
	 */
	private function analyzeController(HttpRequest $request){
		if(empty($request->controller)){
			$defaultController = Configure::getCustomRouter('controller');
			if(isset($request->params['url'][0]) && !empty($request->params['url'][0])){
				$controller = $request->params['url'][0];
				$controllerName = $this->transformController($controller);
				if(Includes::isCustomClassExisted($controllerName, 'controller', $request->app) === true){
					$request->controller = $controller;
					array_shift($request->params['url']);
				}else{
					$request->controller = $defaultController;
				}
			}else{
				$request->controller = $defaultController;
			}
		}
		$request->controllerName = $this->transformController($request->controller);
		return $request;
	}
	/**
	 * analyze the action param
	 * @param Controller $object
	 * @param HttpRequest $request
	 * @return HttpRequest
	 */
	private function analyzeAction(Controller $object,HttpRequest $request){
		if(empty($request->action)){
			$defaultAction = Configure::getCustomRouter('action');
			if(isset($request->params['url'][0]) && !empty($request->params['url'][0])){
				$action = $request->params['url'][0];
				if($this->checkAction($object, $action)){
					$request->action = $action;
					array_shift($request->params['url']);
				}else{
					$request->action = $defaultAction;
				}
			}else{
				$request->action = $defaultAction;
			}
		}
		return $request;
	}
	
	private function checkAction(Controller $object, $action){
		if(!method_exists($object, $action) || !$object->isLicitAccess($action)){
			return false;
		}else{
			return true;
		}
	}
	
	public function initialize($app = ''){
		Includes::useController('App');
		Includes::useController();
		Includes::uses('Object', 'core');
		Includes::useModel('App');
		Includes::useModel();
		Includes::useService('App');
		Includes::useService();
		Includes::useComponent();
		Includes::useOrm('App');
		Includes::useOrm();
		require_once NP_PATH.'exception'.DS.'NpException.class.php';
		if(!defined('APP_PATH')){
			$path = CUSTOM_PATH.Configure::getCustomDirectory('app').DS;
			if(!empty($app)){
				$path .= $app.DS;
			}
			define('CURRENT_APP', $app);
			define('APP_PATH', $path);
		}
		$config = APP_PATH.Configure::getCustomDirectory('config').DS.'config.php';
		if(file_exists($config)){
			include $config;
		}
	}
	private function loadAppConfig($app){
		$config = CUSTOM_PATH.Configure::getCustomDirectory('config').DS.'config.php';
		if(file_exists($config)){
			include $config;
		}
	}
	private function exception($e){
		Includes::useView();
		Includes::useView('App');
		$view = new AppView();
		$view->exception($e);
	}
	private function transformController($controller){
		$class = '';
		if(Configure::$autoTransformController === false){
			$class = $controller;
		}else{
			$ds = isset(Configure::$separator['controller']) ? Configure::$separator['controller'] : '_';
			$tmp = explode($ds, $controller);
			foreach ($tmp as $t){
				$class .= strtoupper(substr($t, 0, 1)).strtolower(substr($t, 1));
			}
		}
		return $class;
	}
	private function getControllerClass($controller){
		if(Configure::$autoSupplementClassname !== false){
			$extension = Configure::getCustomExtension('controller');
			$controller .= $extension;
		}
		return $controller;
	}
}
?>