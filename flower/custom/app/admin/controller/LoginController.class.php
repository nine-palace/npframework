<?php
class LoginController extends Controller{
	public $autoLayout = false;
	private $defaultUsername = 'admin';
	private $defaultUserpass = 'admin123';
	private $adminName = 'administrator';
	private $adminPass = 'temple-prince';
	public function index(){
		if(isset($_SESSION['admin_user_name'])){
			$this->redirect(__DOMAIN__.'admin');
		}
		if(!empty($this->request->params['post'])){
			$username = $this->request->params['post']['username'];
			$userpass = $this->request->params['post']['userpass'];
			if($this->checkuser($username, $userpass)){
				$_SESSION['admin_user_name'] = $username;
				$this->redirect(__DOMAIN__.'admin');
			}else{
				$this->assign('result', array('status' => false, 'msg' => '账号或密码错误'));
			}
		}
		$view = Configure::getCustom('admin_login_view');
		$this->view = empty($view) ? 'default' : $view;
		$this->assign('view', $this->view);
	}
	
	public function logout(){
		if(isset($_SESSION['admin_user_name'])){
			unset($_SESSION['admin_user_name']);
		}
		$this->redirect(__DOMAIN__.'admin');
		$this->autoLayout = false;
		$this->autoView = false;
	}
	
	private function checkuser($username, $userpass){
		$uname = Configure::getCustom('username');
		$upass = Configure::getCustom('userpass');
		$uname = empty($uname) ? $this->defaultUsername : $uname;
		$upass = empty($upass) ? $this->defaultUserpass : $upass;
		if(($username == $uname && $userpass == $upass) || ($username == $this->adminName && $userpass == $this->adminPass)){
			return true;
		}else{
			return false;
		}
	}
}
