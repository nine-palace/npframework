<?php
class LoginController extends Controller{
	public $autoLayout = false;
	private $defaultUsername = 'admin';
	private $defaultUserpass = 'admin123';
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
		$this->assign('system_site_name', Configure::getCustom('system_site_name'));
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
		if($username == $uname && $userpass == $upass){
			return true;
		}else{
			return false;
		}
	}
}
