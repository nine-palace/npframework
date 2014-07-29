<?php
Includes::useComponent('NpDatabase');
class CreateController extends AppController{
	private $link = null;
	public $autoView = false;
	public $autoLayout = false;
	
	private function create(){
		$host = Configure::getCustomDbConfig('host');
		$port = Configure::getCustomDbConfig('port');
		$user = Configure::getCustomDbConfig('user');
		$pwd = Configure::getCustomDbConfig('pwd');
		$link = mysql_connect($host.':'.$port, $user, $pwd);
		$this->link = $link;
	}
	public function index(){
		$this->autoLayout = false;
		$this->autoView = false;
		$this->createDB();
		$this->createTable();
	}
	
	public function createDB(){
		$db = Configure::getCustomDbConfig('prefix').Configure::getCustomDbConfig('dbName');
		$charset = Configure::getCustomDbConfig('charset');
		if(empty($this->link)){
			$this->create();
		}
		$sql = NpDatabaseComponent::dropDatabase($db);
		$res = mysql_query($sql, $this->link);
		if($res){
			echo '<span style="color:green;">Drop database '.$db.' successful.</span><br>';
		}else{
			echo '<span style="color:red;">Drop database '.$db.' failed.</span><br>';
			echo '<span style="color:red;">'.mysql_error($this->link).'</span><br>';
		}
		$sql = NpDatabaseComponent::createDatabase($db);
		$res = mysql_query($sql, $this->link);
		$sql = NpDatabaseComponent::alterCode($db, $charset);
		$res = mysql_query($sql, $this->link);
		$sql = NpDatabaseComponent::useDatabase($db);
		$res = mysql_query($sql, $this->link);
		if($res){
			echo '<span style="color:green;">Create database '.$db.' successful.</span><br>';
		}else{
			echo '<span style="color:red;">Create database '.$db.' failed.</span><br>';
			echo '<span style="color:red;">'.mysql_error($this->link).'</span><br>';
		}
	}
	
	public function createTable(){
		$params = array(
				'user' => array(
						'id' => array('null' => false, 'auto' => true),
						'name' => array('type' => 'varchar', 'length' => '40'),
						'email' => array('type' => 'varchar', 'length' => '100'),
						'telephone' => array('length' => '15'),
						'sex' => array('type' => 'tinyint', 'length' => '1'),
						'birthday' => array('type' => 'datetime', 'length' => false),
						'permission' => array('null' => false, 'value' => '9'),
						'created' => array('type' => 'datetime', 'length' => false)
						),
				'basic' => array(
						'id' => array('null' => false, 'auto' => true),
						'user_id' => array('null' => false),
						'post' => array('type' => 'varchar', 'length' => '255'),
						'city' => array('type' => 'varchar', 'length' => '255'),
						'background' => array('type' => 'varchar', 'length' => '40'),
						'school' => array('type' => 'varchar', 'length' => '40'),
						'salay' => array('type' => 'varchar', 'length' => '40'),
						'subject' => array('type' => 'varchar', 'length' => '40'),
						'created' => array('type' => 'datetime', 'length' => false)
						),
				'experience' => array(
						'id' => array('null' =>false, 'auto' => true),
						'user_id' => array('null' => false),
						'company' => array('type' => 'varchar', 'length' => '255'),
						'start' => array('type' => 'datetime', 'length' => false),
						'end' => array('type' => 'datetime', 'length' => false),
						'post' => array('type' => 'varchar', 'length' => '40'),
						'content' => array('type' => 'text', 'length' => false),
						'project_url' => array('type' => 'varchar', 'length' => '255'),
						'created' => array('type' => 'datetime', 'length' => false) 
						),
				'skills' => array(
						'id' => array('null' => false, 'auto' => true),
						'user_id' => array('null' => false),
						'content' => array('type' => 'text', 'length' => false),
						'created' => array('type' => 'datetime', 'length' => false)
						),
				'evaluation' => array(
						'id' => array('null' => false, 'auto' => true),
						'user_id' => array('null' => false),
						'content' => array('type' => 'text', 'length' => false),
						'created' => array('type' => 'datetime', 'length' => false)
						),
				'control' => array(
						'id' => array('null' => false, 'auto' => true),
						'flag' => array('type' => 'varchar', 'null' => false, 'length' => '20', 'comment' => '控件标识'),
						'name' => array('type' => 'varchar', 'null' => false, 'length' => '32', 'comment' => '控件名称')
						),
				'resume' => array(
						'id' => array('null' => false, 'auto' => true, 'comment' => '简历ID'),
						'name' => array('null' => false, 'type' => 'varchar', 'length' => 40, 'comment' => '简历名称'),
						'user_id' => array('null' => false, 'value' => 0, 'comment' => '用户ID'),
						'template' => array('type' => 'varchar', 'length' => 50, 'null' => false, 'comment' => '模板名称')
						)
				);
		if(empty($this->link)){
			$this->create();
		}
		foreach ($params as $key => $value){
			$sql = NpDatabaseComponent::createTable($key, $value);
			$res = mysql_query($sql, $this->link);
			if($res){
				echo '<span style="color:green;">Create table '.$key.' successful.</span><br>';
			}else{
				echo '<span style="color:red;">Create table '.$key.' failed.</span><br>';
				echo '<span style="color:red;">'.mysql_error($this->link).'</span><br>';
			}
			$this->createOrmFile($key, $value);
			$this->createModelFile($key, $value);
		}
	}
	
	private function createOrmFile($key, $value){
		$path = CUSTOM_PATH.Configure::getCustomDirectory('app').DS;
		if(!empty($this->app)){
			$path .= $this->app.DS;
		}
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
		$path = CUSTOM_PATH.Configure::getCustomDirectory('app').DS;
		if(!empty($this->app)){
			$path .= $this->app.DS;
		}
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
}