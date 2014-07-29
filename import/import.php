<?php
	$dir = 'f:/movie/2000W/';
	if(!file_exists($dir) || !is_dir($dir)){
		echo 'failed!';exit;
	}
	require_once 'f:/wamp/www/synchronous/project/lib/core/Object.class.php';
	require_once 'f:/wamp/www/synchronous/project/lib/component/Component.class.php';
	require_once 'f:/wamp/www/synchronous/project/lib/component/FileComponent.class.php';
	$list = FileComponent::readDir($dir, true, 'csv');
	
	$list = array('f:/movie/2000W/1800w-2000w.csv', 'f:/movie/2000W/last5000.csv');
	$host = 'localhost';
	$port = '3306';
	$user = 'root';
	$pwd = '';
	$link = mysql_connect($host.':'.$port, $user, $pwd);
	if(!$link){
		echo 'DB connect Failed!';exit;
	}
	$r = mysql_select_db('np_hotel', $link);
	$r = mysql_query('set names utf8', $link);
	$i = 0;
	$j = 0;
	foreach ($list as $v){
// 		$handle = fopen($dir.$v, 'r');
		$handle = fopen($v, 'r');
		while(!feof($handle)){
			$t = fgets($handle, 4096);
			$row = filter($t);
			$sql = createSql($row);
			$r = mysql_query($sql, $link);
			if($r == false){
				$j++;
				fclose($handle);
				$h = fopen('error.txt', 'w');
				fwrite($h, $sql);
				fclose($h);
				echo $sql;exit;
			}else{
				$i++;
			}
			if(($i != 0 && $i % 100 == 0) || ($j != 0 && $j % 100 == 0)){
				echo 'success  '.$i.';   failed   '.$j."\n";
			}
		}
		fclose($handle);
	}

	function filter($record){
		$t = explode(',', $record);
		$demo = array('name','cardNo','descriot','ctfTp','ctfId','gender','birthday','address',
				'zip','dirty','district1','district2','district3','district4','district5','district6','firstNm',
				'lastNm','duty','mobile','tel','fax','email','nation','taste','education','company',
				'ctel','caddress','czip','family','version','exten_id'
		);
		$row = array();
		$length = count($demo);
		for($i = 0; $i < $length; $i++){
			if(isset($t[$i])){
				$tmp = str_replace("'", "", $t[$i]);
				$tmp = str_replace("\\", "", $tmp);
				$row[$demo[$i]] = $tmp;
			}
		}
		return $row;
	}
	function createSql($row){
		$sql = "insert into `record`(`";
		$keys = array_keys($row);
		$values = array_values($row);
		$sql .= implode('`,`', $keys)."`) values('".implode("','", $values)."');";
		return $sql;
	}