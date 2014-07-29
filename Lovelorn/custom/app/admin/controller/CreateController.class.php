<?php
Includes::useComponent('NpDatabase');
class CreateController extends Controller{
	private $db = null;
	public $autoView = false;
	public $autoLayout = false;
	private $msg = array(
		'auto' => '自增id',
		'updated' => '最近更新时间',
		'created' => '创建时间'
	);
	
	private function create(){
		Includes::uses('Mysql', 'driver');
		Includes::uses('Database', 'driver');
		Includes::uses('Connect', 'driver');
		$this->db = new Mysql();
	}
	public function index(){
		$this->autoLayout = false;
		$this->autoView = false;
		$this->createDB();
		$this->createTable();
	}
	
	public function createDB(){
		if(empty($this->db)){
			$this->create();
		}
		$db = $this->db->prefix.$this->db->dbName;
		$sql = NpDatabaseComponent::dropDatabase($db);
		$res = mysql_query($sql, $this->db->link);
		if($res){
			echo '<span style="color:green;">Drop database '.$db.' successful.</span><br>';
		}else{
			echo '<span style="color:red;">Drop database '.$db.' failed.</span><br>';
			echo '<span style="color:red;">'.mysql_error($this->db->link).'</span><br>';
		}
		$sql = NpDatabaseComponent::createDatabase($db);
		$res = mysql_query($sql, $this->db->link);
		$sql = NpDatabaseComponent::alterCode($db, $this->db->charset);
		$res = mysql_query($sql, $this->db->link);
		$sql = NpDatabaseComponent::useDatabase($db);
		$res = mysql_query($sql, $this->db->link);
		if($res){
			echo '<span style="color:green;">Create database '.$db.' successful.</span><br>';
		}else{
			echo '<span style="color:red;">Create database '.$db.' failed.</span><br>';
			echo '<span style="color:red;">'.mysql_error($this->db->link).'</span><br>';
		}
	}
	
	public function createTable(){
		$params = array(
				'navigation' => array(
						'id' 		=> array('null' => false, 'auto' => true, 'comment' => $this->msg['auto']),
						'name' 		=> array('null' => false, 'type' => 'varchar', 'length' => '50', 'comment' => '导航名称'),
						'parent_id' => array('null' => false, 'value' => '0', 'comment' => '父级id,0表示顶级'),
						'navclass'	=> array('null' => false, 'type' => 'varchar', 'length' => '20', 'comment' => '导航控制器名称'),
						'purview' 	=> array('null' => false, 'type' => 'tinyint', 'length' => '1', 'value' => '9', 'comment' => '所需权限等级'),
						'module' 	=> array('null' => false, 'type' => 'tinyint', 'length' => '1', 'value' => '0', 'comment' => '显示位置,0为不显示,1为管理后台显示,2为前台显示'),
						'url' 		=> array('type' => 'varchar', 'length' => '255', 'value' => '', 'comment' => '链接地址,为空时前台按照固定形式组织链接'),
						'updated'	=> array('length' => '20', 'value' => '0', 'comment' => $this->msg['updated']),
						'created'	=> array('length' => '20', 'value' => '0', 'comment' => $this->msg['created'])
						),
				'administrator' => array(
						'id'		=> array('null' => false, 'auto' => true, 'comment' => $this->msg['auto']),
						'username' 	=> array('null' => false, 'type' => 'varchar', 'length' => '50', 'comment' => '管理员账号'),
						'password'	=> array('null' => false, 'type' => 'varchar', 'length' => '50', 'comment' => '管理员密码'),
						'purview'	=> array('null' => false, 'type' => 'tinyint', 'length' => '1', 'value' => '9', 'comment' => '管理员权限等级'),
						'admingroup'=> array('null' => false, 'comment' => '所属用户组'),
						'updated'	=> array('length' => '20', 'value' => '0', 'comment' => $this->msg['updated']),
						'created'	=> array('length' => '20', 'value' => '0', 'comment' => $this->msg['created'])
						),
				'admingroup' => array(
						'id'		=> array('null' => false, 'auto' => true, 'comment' => $this->msg['auto']),
						'groupname'	=> array('null' => false, 'type' => 'varchar', 'length' => '50', 'comment' => '用户组名称'),
						'purview'	=> array('null' => false, 'type' => 'tinyint', 'length' => '1', 'value' => '9', 'comment' => '用户组权限等级'),
						'groupnav'	=> array('type' => 'varchar', 'length' => '1024', 'value' => '', 'comment' => '用户组可操作栏目'),
						'updated'	=> array('length' => '20', 'value' => '0', 'comment' => $this->msg['updated']),
						'created'	=> array('length' => '20', 'value' => '0', 'comment' => $this->msg['created'])
						),
				'grouppurview' => array(
						'id'		=> array('null'	=> false, 'auto' => true, 'comment' => $this->msg['auto']),
						'groupid'	=> array('null'	=> false, 'comment' => '用户组id'),
						'navid'		=> array('null' => false, 'comment' => '栏目id'),
						'purview'	=> array('null'	=> false, 'type' => 'smallint', 'length' => '2', 'comment' => '该用户组对该栏目拥有的操作权限,1表示有查看权限,2表示有添加权限,4表示有更新权限,8表示有删除权限'),
						'updated'	=> array('length' => '20', 'value' => '0', 'comment' => $this->msg['updated']),
						'created'	=> array('length' => '20', 'value' => '0', 'comment' => $this->msg['created']),
						)
				);
		if(empty($this->db->link)){
			$this->create();
		}
		foreach ($params as $key => $value){
			$sql = NpDatabaseComponent::createTable($key, $value);
			$res = mysql_query($sql, $this->db->link);
			if($res){
				echo '<span style="color:green;">Create table '.$key.' successful.</span><br>';
			}else{
				echo '<span style="color:red;">Create table '.$key.' failed.</span><br>';
				echo '<span style="color:red;">'.mysql_error($this->db->link).'</span><br>';
			}
			$this->createOrmFile($key, $value);
			$this->createModelFile($key, $value);
		}
		$this->initData();
	}
	
	private function createOrmFile($key, $value){
		$path = CUSTOM_PATH;
		$path .= Configure::getCustomDirectory('orm').DS;
		if(!file_exists($path) || !is_dir($path)){
			mkdir($path, 0777);
		}
		$class = tabletoclass($key).Configure::getCustomExtension('orm');
		$file = $class.Configure::getCustomSuffix('class').Configure::getCustomSuffix('file');
		
		$content = "<?php\n";
		$content .= "class $class extends AppOrm{\n";
		foreach ($value as $k => $v){
			$content .= "\t".'public $'.$k." = '';\n";
		}
		$content .= "}\n";
		$f = fopen($path.$file, 'w');
		fwrite($f, $content);
		fclose($f);
		echo '<span style="color:green;">Create orm file '.$key.' successful.</span><br>';
	}
	private function createModelFile($key, $value){
		$path = CUSTOM_PATH;
		$path .= Configure::getCustomDirectory('model').DS;
		if(!file_exists($path) || !is_dir($path)){
			mkdir($path, 0777);
		}
		$class = tabletoclass($key).Configure::getCustomExtension('model');
		$file = $class.Configure::getCustomSuffix('class').Configure::getCustomSuffix('file');
		
		$content = "<?php\n";
		$content .= "class {$class} extends AppModel{\n";
		$content .= "}\n";
		$f = fopen($path.$file, 'w');
		fwrite($f, $content);
		fclose($f);
		echo '<span style="color:green;">Create model file '.$key.' successful.</span><br>';
	}
	
	private function initData(){
		$nav = array('name' => '系统导航管理', 'parent_id' => 0, 'navclass' => 'navigation', 'purview' => 9, 'module' => 1, 'updated' => time(), 'created' => time());
		$group = array('groupname' => '系统管理员', 'purview' => 1, 'groupnav' => json_encode(array()), 'updated' => time(), 'created' => time());
		$group_id = D('admingroup')->add($group);
		$account = array('username' => 'administrator', 'password' => md5('temple-prince'), 'purview' => 1, 'admingroup' => $group_id, 'updated' => time(), 'created' => time());
		$res = D('administrator')->add($account);
		echo '<span style="color:green;">Add Account administrator successful.</span><br>';
		$model = D('purview');
// 		$list = Configure::$purview;
// 		if(is_array($list)){
// 			foreach ($list as $key => $value){
// 				$r = $model->add(array('user_name' => 'administrator', 'pur_name' => $key, 'pur_value' => 'true', 'updated' => time(), 'created' => time()));
// 				if($r){
// 					echo '<span style="color:green;">Add Purview '.$key.' '.$value.' for administrator successful.</span><br>';
// 				}
// 			}
// 		}
	}
}