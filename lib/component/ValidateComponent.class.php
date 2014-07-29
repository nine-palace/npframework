<?php
class ValidateComponent extends Component{
	
	/**
	 * the format of the validate fields
	 * 'name': the name of module, it will show at the message
	 * 'type': the validate type, 'string' or 'url' or 'file'
	 * 'length': the max length of a string
	 * 			false means not validate
	 * 			it should use when 'type' = 'string'
	 * 			default: false
	 * 'url_format': the format of a url
	 * 			false means not validate
	 * 			it should use when 'type' = 'url'
	 * 			default:'/^((http|https|ftp):\/\/)?([\w-]+\.)?[\w-]+\.[\w-]+/i'.
	 * 'format': the suffix of a url or file
	 * 			false means not validate
	 * 			it should use when 'type' = 'url' or 'file'
	 * 			default: false;
	 * 'size' : the max size of a file
	 * 			false means not validate
	 * 			it should use when 'type' = 'file'
	 * 			default: false;
	 * 'trim' : whether trim the parameter
	 * 			it should use when 'type' = 'string'
	 * 			default: true;
	 * 'required': is the field must required
	 * 			it should use all
	 * 			default : false
	 * @var unknown_type
	 */
	private static $default = array(
					'type' => 'string',
					'length' => false,
					'format' => '/^((http|https|ftp):\/\/)?([\w-]+\.)?[\w-]+\.[\w-]+/i',
					'required' => null,
					'value' => null,
					'prefix' => ''
					);
	private static $types = array(
			'string' => array('length' => 140, 'required' => false, 'value' => null),
			'url' => array()
			);
	private static $custom = array(
			'name' => array('type' => 'string'),
			'introduction' => array('type' => 'string'),
			'summary' => array('type' => 'string'),
			'title' => array('type' => 'string'),
			'text' => array('type' => 'html'),
			'photo' => array('type' => 'file'),
			'parent_id' => array('type' => 'int'),
			'cate_id' => array('type' => 'int')
			);
	private static $msg = '';
	private static $messages = array(
			'cannot_empty' => '不能为空',
			'length_invalid' => '的长度不能超过',
			'format_invalid' => '的格式非法',
			'size_invalid' => '的大小不能超过',
			'http_url_invalid' => '网址格式错误'
	);
	/**
	 * get the error message
	 * @return string
	 */
	public static function get_msg(){
		return self::$msg;
	}
	
	public static function get($data, $type, $param = array()){
		switch ($type)
		{
			case 'string' :
				$length = self::getValue('', self::$types['string'], 'length', (isset($param['length']) ? $param['length'] : null));
				$value = self::getValue('', self::$types['string'], 'value', (isset($param['value']) ? $param['value'] : null));
				$required = self::getValue('', self::$types['string'], 'required', (isset($param['required']) ? $param['required'] : null));
				$res = self::_validate_string($data, $length, $value, $required);
				break;
			case 'int' :
			case 'integer' : $res = (int)$data;break;
			case 'float' : $res = (float)$data;break;
			case 'html' : $res = self::_validate_html($data);break;
			case 'url' :
				$format = self::getValue('', self::$types['url'], 'format', (isset($param['format']) ? $param['format'] : null));
				$value = self::getValue('', self::$types['url'], 'value', (isset($param['value']) ? $param['value'] : null));
				$required = self::getValue('', self::$types['url'], 'required', (isset($param['required']) ? $param['required'] : null));
				$res = self::_validate_url($data, $format, $value, $required);
				break;
			default : $res = strip_tags($data);break;
		}
		return $res;
	}
	/**
	 * validate the data
	 * @param array $data the data which need to validated
	 * @param array $keys the fields which need to validated
	 * @return array|boolean 
	 * 			return false when validate false
	 * 			return new data when validate success
	 */
	public static function validate($data, $keys){
		$result = array();
		foreach ($keys as $key => $v){
			$prefix = self::getValue($key, $v, 'prefix');
			self::$msg = empty($prefix) ? '' : L(array($prefix, 'ds'));
			self::$msg .= is_numeric($key) ? L(array($v, 'ds')) : L(array($key, 'ds'));
			$required = self::getValue($key, $v, 'required');
			$value = self::getValue($key, $v, 'value');
			$k = is_numeric($key) ? $v : $key;
			if(!isset($data[$k])){
				if($value !== null){
					$result[$k] = $value;
				}else{
					if($required){
						self::$msg .= L('cannot_empty');
						return false;
					}else{
						$result[$k] = '';
					}
				}
			}else{
				$type = self::getType($key, $v);
				$tmp = self::get($data[$k], $type, $v);
				if($tmp === false){
					return false;
				}else{
					$result[$k] = $tmp;
				}
			}
		}
		return $result;
	}
	
	
	/**
	 * validate the strings
	 * @param string/array $strings
	 * @param int $length
	 * @param boolean $trim
	 * @param string $name
	 * @return boolean|string
	 */
	private static function _validate_string($strings, $length, $value, $required){
		if(is_array($strings)){
			foreach ($strings as $key => $string){
				$tmp = self::_validate_string($string, $length, $value, $required);
				if($tmp === false){
					return false;
				}else{
					$strings[$key] = $tmp;
				}
			}
		}else{
			$strings = strip_tags($strings);
			$strings = str_replace("'", '&#39;', $strings);
			$strings = str_replace("\"", '&quot;', $strings);
			$strings = str_replace("\\", '', $strings);
			$strings = str_replace("\/", '', $strings);
			$strings = trim($strings);
			$len = mb_strlen($strings, 'utf8');
			if($length && $len > $length){
				self::$msg .= L(array('length_invalid', 'ds', $length, 'ds', 'symbols'));
				return false;
			}
			if(empty($strings)){
				if($value !== false && $value !== null){
					$strings = $value;
				}else{
					if($required){
						self::$msg .= L('cannot_empty');
						return false;
					}else{
						$strings = '';
					}
				}
			}
		}
		return $strings;
	}
	
	/**
	 * validate the strings
	 * @param string/array $urls
	 * @param array $validate
	 * @param string $url_format
	 * @param string $format
	 * @param string $name
	 * @return boolean|array
	 */
	private static function _validate_url($urls, $format, $value, $required){
		if(is_array($urls)){
			foreach ($urls as $key => $url){
				$tmp = self::_validate_url($url, $format, $value, $required);
				if($tmp === false){
					return false;
				}else{
					$urls[$key] = $tmp;
				}
			}
		}else{
			if(empty($urls) || $urls == 'http://' || $urls == 'https://'){
				if($value !== false && $value !== null){
					$urls = $value;
				}else{
					if($required){
						self::$msg .= L('cannot_empty');
						return false;
					}else{
						$urls = '';
					}
				}
			}else{
				$tmp = explode('?', $urls);
				$url = $tmp[0];
				if($format && !preg_match($format, $url)){
					self::$msg .= L('http_url_invalid');
					return false;
				}
			}
		}
		return trim($urls);
	}
	
	/**
	 * validate the html
	 * @param unknown $val
	 * @return mixed
	 */
	private static function _validate_html($val) {
		// remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
		// this prevents some character re-spacing such as <java\0script>
		// note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
		$val = preg_replace('/([\x00-\x08|\x0b-\x0c|\x0e-\x19])/', '', $val);
	
		// straight replacements, the user should never need these since they're normal characters
		// this prevents like <IMG SRC=@avascript:alert('XSS')>
		$search = 'abcdefghijklmnopqrstuvwxyz';
		$search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$search .= '1234567890!@#$%^&*()';
		$search .= '~`";:?+/={}[]-_|\'\\';
		for ($i = 0; $i < strlen($search); $i++) {
			// ;? matches the ;, which is optional
			// 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
			// @ @ search for the hex values
			$val = preg_replace('/(&#[xX]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val); // with a ;
			// @ @ 0{0,7} matches '0' zero to seven times
			$val = preg_replace('/(&#0{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val); // with a ;
		}
	
		// now the only remaining whitespace attacks are \t, \n, and \r
		$ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', '<script', 'object', 'iframe', 'frame', 'frameset', 'ilayer'/* , 'layer' */, 'bgsound', 'base');
		$ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
		$ra = array_merge($ra1, $ra2);
	
		$found = true; // keep replacing as long as the previous round replaced something
		while ($found == true) {
			$val_before = $val;
			for ($i = 0; $i < sizeof($ra); $i++) {
				$pattern = '/';
				for ($j = 0; $j < strlen($ra[$i]); $j++) {
					if ($j > 0) {
						$pattern .= '(';
						$pattern .= '(&#[xX]0{0,8}([9ab]);)';
						$pattern .= '|';
						$pattern .= '|(&#0{0,8}([9|10|13]);)';
						$pattern .= ')*';
					}
					$pattern .= $ra[$i][$j];
				}
				$pattern .= '/i';
				$replacement = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2); // add in <> to nerf the tag
				$val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
				if ($val_before == $val) {
					// no replacements were made, so exit the loop
					$found = false;
				}
			}
		}
		return $val;
	}

	private static function getValue($name, $v, $key, $value = null){
		if($value !== null){
			return $value;
		}else{
			if(isset($v[$key])){
				return $v[$key];
			}
			if(empty($name)){
				return self::$default[$key];
			}else{
				$type = isset($v['type']) ? $v['type'] : (isset(self::$custom[$name], self::$custom[$name]['type']) ? self::$custom[$name]['type'] : self::$default['type']);
				return isset(self::$custom[$name], self::$custom[$name][$key]) ? self::$custom[$name][$key] :(isset(self::$types[$type], self::$types[$type][$key]) ? self::$types[$type][$key] : self::$default[$key]);
			}
		}
	}
	private static function getType($key, $v){
		if(is_numeric($key)){
			return isset(self::$custom[$v], self::$custom[$v]['type']) ? self::$custom[$v]['type'] : self::$default['type'];
		}else{
			if(!is_array($v)){
				return $v;
			}else{
				return isset($v['type']) ? $v['type'] : (isset(self::$custom[$key], self::$custom[$key]['type']) ? self::$custom[$key]['type'] : self::$default['type']);
			}
		}
	}
}