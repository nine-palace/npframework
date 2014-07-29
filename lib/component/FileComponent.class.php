<?php
class FileComponent extends Component{
	
	public static function readCSV($file = '', $dir = ''){
		
	}
	
	public static function readFile($file, $suffix = false){
		if(!file_exists($file) || is_file($file)){
			return false;
		}
		if($suffix != false && $suffix != self::getSuffix($file)){
			return false;
		}
		
	}
	
	/**
	 * get the files from a directory
	 * @param string $dir
	 * @param boolean $suffix_need
	 * @param string $suffix
	 * @param $deep
	 */
	public static function readDir($dir, $suffix_need = true, $suffix = false, $deep = 0){
		$result = array();
		if(file_exists($dir) && is_dir($dir)){
			$t = opendir($dir);
			while(($file = readdir($t)) != false){
				if($file != "." && $file != ".."){
					if(is_dir($file)){
						if(is_numeric($deep) && $deep > 0){
							$result[$file] = self::readDir($dir.DS.$file, $suffix_need, $suffix, $deep - 1);
						}else{
							$result[$file] = self::readDir($dir.DS.$file, $suffix_need, $suffix, $deep);
						}
					}else{
						if($suffix !== false){
							if($suffix != self::getSuffix($file)){
								continue;
							}
						}
						$result[] = $suffix_need == true ? $file : self::getFilenameWithoutSuffix($file);
					}
				}
			}
		}
		return $result;
	}
	/**
	 * get the suffix of a filename
	 * @param string $filename
	 * @return string
	 */
	public static function getSuffix($filename){
		$ns = explode('.', $filename);
		return isset($ns[1]) ? $ns[1] : '';
	}
	/**
	 * get the filename with out suffix
	 * @param string $filename
	 * @return string
	 */
	public static function getFilenameWithoutSuffix($filename){
		$ns = explode(',', $filename);
		return $ns[0];
	}
	
	public static function downloadFile($file, $download_name = ''){
		$file_a = file_get_contents($file);
		Header("Content-type: application/octet-stream" );
		Header("Content-type: application/vnd.android.package-archive" );
		Header("Content-Length:".strlen($file_a));
		Header('Content-Disposition: attachment; filename='.$download_name);
		exit($file_a);
	}
}