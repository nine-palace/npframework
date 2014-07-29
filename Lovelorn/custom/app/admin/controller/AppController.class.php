<?php
Includes::useComponent('Validate');
Includes::useComponent('SUtil');
Includes::useComponent('Session');
Class AppController extends Controller{
	public $layout = 'admin';
	public $template = 'springtime';
	public $model = '';
	protected $id = '';
	protected $limit = 10;
	protected $page = 1;
	public $refuseAccessAction = array('isLogin', 'showMsg', 'pages', 'afterModify');
	public function initialize(){
		if(!$this->isLogin()){
			$this->redirect(__DOMAIN__.'admin/login.html');
		}
	}
	
	public function showMsg($msg = '', $boolean = true){
		$this->assign('return_message', array('status' => $boolean, 'msg' => $msg));
	}
	
	public function isLogin(){
		if(!SessionComponent::have(array('admin_user_name', 'admin_user_id', 'admin_user_perview'))){
			return false;
		}else{
			$this->current['admin_user_name'] = SessionComponent::read('admin_user_name');
			$this->current['admin_user_id'] = SessionComponent::read('admin_user_id');
			$this->current['admin_user_perview'] = SessionComponent::read('admin_user_perview');
			return true;
		}
	}

}