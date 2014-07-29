<?php
class Object{
	
	public function setLanguage($language){
		$_SESSION[Configure::$sessionKeyForLanguage] = $language;
	}
	
	public function getLanguage(){
		$lang = empty($_SESSION[Configure::$sessionKeyForLanguage]) ? $_SESSION[Configure::$sessionKeyForLanguage] : Configure::$defaultLanguage;
		return $lang;
	}
	
	public function _redirect($url){
		header("Location:{$url}");
		exit;
	}
	
	public function multiLanguageFilter($data){
		Includes::useComponent('Pinyin');
		$result = array();
		if(empty($data)){
			return false;
		}
		$lang = $this->getLanguage();
	
		if(!is_array($data)){
			$data = array($lang => $data);
		}
		$tmp = '';
		if(isset($data['chinese'])){
			$tmp = PinyinComponent::Pinyin($data['chinese']);
		}else{
			$flag = true;
			foreach ($data as $k => $v){
				if(!empty($v) && $flag){
					$tmp = PinyinComponent::Pinyin($v);
					$flag = false;
					break;
				}
			}
			if($flag){
				return false;
			}
		}
		if(Configure::$multiLanguage !== true){
			$result[$lang] = isset($data[$lang]) && !empty($data[$lang]) ? $data[$lang] : $tmp;
		}else{
			foreach (Configure::$availableLanguages as $key => $value){
				if(!isset($result[$key])){
					$result[$key] = isset($data[$key]) && !empty($data[$key]) ? $data[$key] : $tmp;
				}
			}
		}
		return SUtilComponent::compress($result);
	}
	

	public function singleLanguageFilter($data, $key = 'name', $flag = true, $single = true){
		if($flag === false){
			return $this->_singleLanguageFilter($data, $key, $single);
		}else{
			$res = array();
			foreach ($data as $k => $v){
				$res[$k] = $this->_singleLanguageFilter($v, $key, $single);
			}
			return $res;
		}
	}
	
	protected function _singleLanguageFilter($info, $key, $single = true){
		if(isset($info[$key])){
			Includes::useComponent('SUtil');
			$tmp = SUtilComponent::uncompress($info[$key]);
			if($single){
				$lang = $this->getLanguage();
				if(isset($tmp[$lang])){
					$info[$key] = $tmp[$lang];
				}else{
					foreach ($tmp as $k => $v){
						if(!empty($v)){
							$info[$key] = $v;
							break;
						}
					}
				}
			}else{
				$info[$key] = $tmp;
			}
		}
		return $info;
	}
}
?>