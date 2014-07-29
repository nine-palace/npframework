<?php
Includes::useComponent('NpDatabase');
class CreateController extends Controller{
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
				'category' => array(
						'id' => array('null' =>false, 'auto' => true),
						'name' => array('null' => false, 'type' => 'text'),
						'note' => array('type' => 'varchar', 'length' => '255'),
						'updated' => array('length' => '20', 'value' => '0'),
						'created' => array('length' => '20', 'value' => '0') 
						),
				'interface' => array(
						'id' => array('null' => false, 'auto' => true),
						'title' => array('null' => false, 'type' => 'varchar', 'length' => '150'),
						'name' => array('type' => 'varchar', 'length' => '255'),
						'params' => array('type' => 'text', 'length' => false),
						'note' => array('type' => 'text', 'length' => false),
						'updated' => array('length' => '20', 'value' => '0'),
						'created' => array('length' => '20', 'value' => '0')
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
// 		$this->initData();
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
		$account = array('name' => 'administrator', 'password' => md5('temple-prince'), 'level' => 9, 'updated' => time(), 'created' => time());
		$res = D('account')->add($account);
		echo '<span style="color:green;">Add Account administrator successful.</span><br>';
		$model = D('purview');
		$list = Configure::$purview;
		if(is_array($list)){
			foreach ($list as $key => $value){
				$r = $model->add(array('user_name' => 'administrator', 'pur_name' => $key, 'pur_value' => 'true', 'updated' => time(), 'created' => time()));
				if($r){
					echo '<span style="color:green;">Add Purview '.$key.' '.$value.' for administrator successful.</span><br>';
				}
			}
		}
	}
}