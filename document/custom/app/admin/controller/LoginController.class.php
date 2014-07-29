<?php
Includes::useComponent('Session');
class LoginController extends Controller{
	public $autoLayout = false;
	private $nameKey = 'admin_user_name';
	public function index(){
		if(SessionComponent::have($this->nameKey)){
			$this->redirect(__DOMAIN__.'admin');
		}
		if(!empty($this->request->params['post'])){
			$username = $this->request->params['post']['username'];
			$userpass = $this->request->params['post']['userpass'];
			if($this->checkuser($username, $userpass)){
				$this->redirect(__DOMAIN__.'admin');
			}else{
				$this->assign('result', array('status' => false, 'msg' => L(array('account','ds', 'or', 'ds', 'password', 'ds', 'error'))));
			}
		}
		$view = Configure::getCustom('admin_login_view');
		$this->view = empty($view) ? 'default' : $view;
		$this->assign('view', $this->view);
	}
	
	public function logout(){
		if(SessionComponent::have($this->nameKey)){
			SessionComponent::delete($this->nameKey);
		}
		SessionComponent::delete('purview');
		$this->redirect(__DOMAIN__.'admin');
		$this->autoLayout = false;
		$this->autoView = false;
	}
	
	private function checkuser($username, $userpass){
		$uname = $username;
		$upass = md5($userpass);
		$user = D('account')->find(array('name' => $uname));
		if(isset($user[0], $user[0]['password']) && $upass == $user[0]['password']){
			SessionComponent::write('admin_user_name', $uname);
			SessionComponent::write('admin_user_id', $user[0]['id']);
			SessionComponent::write('admin_user_level', $user[0]['level']);
			$purview = array();
			$tmp = D('purview')->find(array('user_name' => $uname), array('pur_name', 'pur_value'));
			foreach ($tmp as $v){
				$purview[$v['pur_name']] = $v['pur_value'];
			}
			SessionComponent::write('purview', $purview);
			return true;
		}else{
			return false;
		}
	}
}
