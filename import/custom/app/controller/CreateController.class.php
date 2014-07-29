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
				'record' => array(
						'id' => array('null' =>false, 'auto' => true),
						'name' => array('null' => false, 'type' => 'varchar', 'length' => '50'),
						'cardNo' => array('type' => 'varchar', 'length' => '50'),
						'descriot' => array('type' => 'varchar', 'length' => '50'),
						'ctfTp' => array('type' => 'varchar', 'length' => '10'),
						'ctfId' => array('type' => 'varchar', 'length' => '50'),
						'gender' => array('type' => 'varchar', 'length' => '5'),
						'birthday' => array('type' => 'varchar', 'length' => '15'),
						'address' => array('type' => 'varchar', 'length' => '255'),
						'zip' => array('type' => 'varchar', 'length' => '15'),
						'dirty' => array('type' => 'varchar', 'length' => '20'),
						'district1' => array('type' => 'varchar', 'length' => '20'),
						'district2' => array('type' => 'varchar', 'length' => '20'),
						'district3' => array('type' => 'varchar', 'length' => '20'),
						'district4' => array('type' => 'varchar', 'length' => '20'),
						'district5' => array('type' => 'varchar', 'length' => '20'),
						'district6' => array('type' => 'varchar', 'length' => '20'),
						'firstNm' => array('type' => 'varchar', 'length' => '20'),
						'lastNm' => array('type' => 'varchar', 'length' => '20'),
						'duty' => array('type' => 'varchar', 'length' => '20'),
						'mobile' => array('type' => 'varchar', 'length' => '20'),
						'tel' => array('type' => 'varchar', 'length' => '20'),
						'fax' => array('type' => 'varchar', 'length' => '20'),
						'email' => array('type' => 'varchar', 'length' => '20'),
						'nation' => array('type' => 'varchar', 'length' => '20'),
						'taste' => array('type' => 'varchar', 'length' => '20'),
						'education' => array('type' => 'varchar', 'length' => '20'),
						'company' => array('type' => 'varchar', 'length' => '20'),
						'ctel' => array('type' => 'varchar', 'length' => '20'),
						'caddress' => array('type' => 'varchar', 'length' => '20'),
						'czip' => array('type' => 'varchar', 'length' => '20'),
						'family' => array('type' => 'varchar', 'length' => '20'),
						'version' => array('type' => 'varchar', 'length' => '30'),
						'exten_id' => array('type' => 'varchar', 'length' => '20')
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
}