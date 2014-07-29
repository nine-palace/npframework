<?php
class View extends Object{
	private $params = array();
	private $tmpParams = array();
	private $content_for_js = '';
	private $content_for_css = '';
	private $content_for_layout = '';
	private $content = '';
	private $filemtime = false;
	private $msg = array(
			'view' => 'template_not_found',
			'layout' => 'layout_not_found',
			'default' => 'unknow_error'
			);
	public function __construct(){
	}
	public function exception($e){
		$this->_redirect(__DOMAIN__);
	}
	final function setData(array $data){
		$this->params = $data;
	}
	
	final function display($template, $layout, $name = '', $app = '', $style_for_template = ''){
		$this->setCurrentUrl($name, $style_for_template);
		extract($this->params);
		$compileFile = $this->getCompilePath($template, $app, 'view', $name);
		if(!file_exists($compileFile) || !is_file($compileFile) || Configure::$autoCacheCompileFile === false){
			$content = $this->getFileContent($template, 'view', $name, $style_for_template);
			$this->writeFile($content, $compileFile);
		}else{
			if(Configure::$autoUpdateCompileFile === true){
				$compileTime = filemtime($compileFile);
				$viewTime = $this->getFileTime($template, 'view', $name, $style_for_template);
				if($compileTime && $viewTime['updatetime'] && $viewTime['updatetime'] > $compileTime){
					$content = $this->getFileContent($template, 'view', $name, $style_for_template);
					$this->writeFile($content, $compileFile);
				}
			}
		}
		if(Configure::$autoLayout === true){
			ob_start();
			require $compileFile;
			$content = ob_get_clean();
			$this->tmpParams['content_for_layout'] = $content;
			if(Configure::$autoLayoutFromStyleTemplate == true && !empty($style_for_template)){
				$layout = strtolower($style_for_template);
				$style_for_template = '';
			}
			$compileFile = $this->getCompilePath($layout, $app, 'layout', $name);
			if(!file_exists($compileFile) || !is_file($compileFile) || Configure::$autoCacheCompileFile === false){
				$content = $this->getFileContent($layout, 'layout', $name, $style_for_template);
				$this->writeFile($content, $compileFile);
			}else{
				if(Configure::$autoUpdateCompileFile === true){
					$compileTime = filemtime($compileFile);
					$layoutTime = $this->getFileTime($layout, 'layout', $name, $style_for_template);
					if($compileTime && $layoutTime['updatetime'] && $layoutTime['updatetime'] > $compileTime){
						$content = $this->getFileContent($layout, 'layout', $name, $style_for_template);
						$this->writeFile($content, $compileFile);
					}
				}
			}
		}
		$this->tmpParams['content_for_js'] = $this->content_for_js;
		$this->tmpParams['content_for_css'] = $this->content_for_css;
		extract($this->tmpParams);
		require $compileFile;
	}
	/**
	 * the start of javascript code
	 */
	final function jsStart(){
		ob_start();
	}
	
	/**
	 * the end of javascript code;
	 */
	final function jsEnd(){
		$js = ob_get_clean();
		if(Configure::$autoSupplementLabel === true){
			$return = '<script type="text/javascript">'.$js.'</script>';
		}else{
			$return = $js;
		}
		$this->content_for_js = $return;
	}
	
	/**
	 * the start of css code
	 */
	final function cssStart(){
		ob_start();
	}
	
	/**
	 * the end of css code
	 */
	final function cssEnd(){
		$css = ob_get_clean();
		if(Configure::$autoSupplementLabel === true){
			$return = '<style type="text/css">'.$css.'</style>';
		}else{
			$return = $css;
		}
		$this->content_for_css .= $return;
	}
	
	private function setCurrentUrl($name, $style_for_template = ''){	
		if(!defined('__VIEW__')){
			$tmp = __APP__.Configure::getCustomDirectory('view').DS;
			if(!empty($style_for_template)){
				$tmp .= $style_for_template.DS;
			}
			if(!empty($name)){
				$tmp .= $name.DS;
			}
			define('__VIEW__', $tmp);
		}
	}
	
	private function changeWorkPath($app, $module, $name){
		$currentDIR = APP_ROOT_PATH;
	}
	
	protected function getTemplateFile($filename, $module, $name, $style_for_tempalte = '', $suffix = ''){
		$this->content = $this->getFileContent($filename, $module, $name, $style_for_tempalte);
		$time = $this->getFileTime($filename, $module, $name, $style_for_tempalte, $suffix);
		$this->filemtime = $time['updatetime'];
	}
	
	/**
	 * generate a absolute path of a compile file
	 * @param string $filename template file name not contains suffix
	 * @param string $app current application name
	 * @param string $name directory name of view
	 * @param string $suffix suffix of compile file, if not set, it will get value from Configure class by $module = 'compile'.
	 * @param string $path start path
	 * @return string
	 */
	protected function getCompilePath($filename, $app, $module, $name, $suffix = ''){
		$filename = $this->getCompileFileName($filename, $app, $module,$name, $suffix);
		return $this->getFilePath($filename, CUSTOM_PATH, 'compile', $app.'_'.$module, $name, true);
	}
	
	/**
	 * get the content of a file
	 * @param string $filename file name not contains suffix
	 * @param string $module file module. 'layout', 'view' and so on.
	 * @param string $name directory name of view
	 * @param string $app current application name, if not set, it will get value from the constant 'CURRENT_APP'
	 * @param string $suffix file suffix, if not set, it will get value from Configure class by $module
	 * @param string $path start path, if not set , it will get value from current work path
	 * @return unknown
	 */
	protected function getFileContent($filename, $module, $name, $style_for_template = '', $suffix = ''){
		if(empty($suffix)){
			$suffix = Configure::getCustomSuffix($module);
		}
		$filename .= $suffix;
		$content = false;
		if($module != 'layout'){
			$content = $this->_getFileContent($this->getFilePath($filename, APP_PATH, $module, $style_for_template, $name));
		}
		if($content === false){
			$content = $this->_getFileContent($this->getFilePath($filename, APP_PATH, $module, $style_for_template));
		}
		if($content === false){
			$content = $this->_getFileContent($this->getFilePath($filename, CUSTOM_PATH, $module, $style_for_template));
		}
		if($content === false){
			$path = empty($style_for_template) ? NP_PATH.$module.DS : NP_PATH.$module.DS.$style_for_template.DS;
			$content = $this->_getFileContent($this->getFilePath($filename, $path));
		}
		if($content === false){
			$msg = isset($this->msg[$module]) ? $this->msg[$module] : $this->msg['default'];
			throw new FileNotFoundException($module, $filename);
		}
		return $content;
	}
	protected function getFileTime($filename, $module, $name, $style_for_template = '', $suffix = ''){
		if(empty($suffix)){
			$suffix = Configure::getCustomSuffix($module);
		}
		$filename .= $suffix;
		$updatetime = false;
		$createtime = false;
		if($module != 'layout'){
			$file = $this->getFilePath($filename, APP_PATH, $module, $style_for_template, $name);
			if(file_exists($file) && is_file($file)){
				$updatetime = filemtime($file);
				$createtime = filectime($file);
			}
		}
		if($updatetime === false && $createtime === false){
			$file = $this->getFilePath($filename, APP_PATH, $module, $style_for_template);
			if(file_exists($file) && is_file($file)){
				$updatetime = filemtime($file);
				$createtime = filectime($file);
			}
		}
		if($updatetime === false && $createtime === false){
			$file = $this->getFilePath($filename, CUSTOM_PATH, $module, $style_for_template);
			if(file_exists($file) && is_file($file)){
				$updatetime = filemtime($file);
				$createtime = filectime($file);
			}
		}
		if($updatetime === false && $createtime === false){
			$path = empty($style_for_template) ? NP_PATH.$module.DS : NP_PATH.$module.DS.$style_for_template.DS;
			$file = $this->getFilePath($filename, $path);
			if(file_exists($file) && is_file($file)){
				$updatetime = filemtime($file);
				$createtime = filectime($file);
			}
		}
		return array('updatetime' => $updatetime, 'createtime' => $createtime);
	}
	/**
	 * get a absolute path of a file 
	 * @param string $filename file name contains suffix
	 * @param string $path start path, if empty, it will get value from current work path.
	 * @param string $module1 first module
	 * @param string $app current application name
	 * @param string $module2 second module
	 * @param string $name the directory name of view
	 * @param boolean $autoCreateDirectory whether auto create directory when it doesn't exist
	 * @return string
	 */
	private function getFilePath($filename, $path, $module = '', $style_for_template = '', $name = '', $autoCreateDirectory = false){
		if(!empty($module)){
			$path .= Configure::getCustomDirectory($module).DS;
			if($autoCreateDirectory !== false){
				if(!file_exists($path) || !is_dir($path)){
					mkdir($path, 0777);
				}
			}
		}
		if(!empty($style_for_template)){
			$path .= $style_for_template.DS;
			if($autoCreateDirectory !== false){
				if(!file_exists($path) || !is_dir($path)){
					mkdir($path, 0777);
				}
			}
		}
		if(!empty($name)){
			$path .= $name.DS;
			if($autoCreateDirectory !== false){
				if(!file_exists($path) || !is_dir($path)){
					mkdir($path, 0777);
				}
			}
		}
		$path .= $filename;
		return $path;
	}
	/**
	 * generate a compile file name contains it's suffix
	 * @param string $templateFileName the name of template file
	 * @param string $app current application name
	 * @param string $name the directory name of view
	 * @param string $suffix
	 * @return string
	 */
	private function getCompileFileName($templateFileName, $app, $module, $name, $suffix){
		if(empty($suffix)){
			$suffix = Configure::getCustomSuffix('compile');
		}
		return md5($app.'_'.$module.'_'.$name.'_'.$templateFileName).$suffix;
	}
	
	private function _getFileContent($filename){
		if(!file_exists($filename) || !is_file($filename)){
			return false;
		}else{
			return file_get_contents($filename);
		}
	}
	/**
	 * write some information to a file
	 * @param string $content
	 * @param string $file
	 * @return boolean
	 */
	private function writeFile($content, $file){
		$f = fopen($file, 'w');
		if($f === false){
			return false;
		}else{
			fwrite($f, $content);
			fclose($f);
		}
		return true;
	}
}
?>