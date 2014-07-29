<?php 
Includes::useComponent('SUtil');
class Websocket{
	protected $master;
	protected $sockets = array();
	protected $users = array();
	protected $debug = true;
	protected $handshakeKey = "258EAFA5-E914-47DA-95CA-C5AB0DC85B11";
	public function __construct($address = 'localhost', $port = 12345, $backlog = 20){
		error_reporting(E_ALL);
		set_time_limit(0);
		ob_implicit_flush(true);
		$this->beforeCreate();
		$this->create($address, $port, $backlog);
		while(true){
			$this->run($this->master);
		}
	}
	/**
	 * you can override this function
	 */
	public function beforeCreate(){
		
	}
	/**
	 * you can override this function
	 * @param unknown $socket
	 * @param unknown $data
	 * @return unknown
	 */
	public function beforeSend($socket, $data){
		return $data;
	}
	protected function connect($socket){
		$this->addUser($socket);
		array_push($this->sockets, $socket);
		$this->console("{$socket} CONNECTED!");
	}
	public function addUser($socket){
		$user = new WebSocketUser();
		$user->id = uniqid();
		$user->socket = $socket;
		array_push($this->users, $user);
	}
	protected function console($msg = ""){
		if($this->debug){
			echo $msg."\n";
		}
	}
	protected function create($address = 'localhost', $port = 12345, $backlog = 20){
		$this->master = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("socket_create failed");
		socket_set_option($this->master, SOL_SOCKET, SO_REUSEADDR, 1) or die("socket_option failed");
		socket_bind($this->master, $address, $port) or die("socket_bind failed");
		socket_listen($this->master, $backlog) or die("socket_listen failed");
		$this->sockets[] = $this->master;
		$date = date('Y-m-d H:i:s');
		$this->console("Server Started : {$date}\n");
		$this->console("Master socket  : {$this->master}\n");
		$this->console("Listen on      : {$address} port {$port}\n\n");
	}
	protected function disconnect($socket){
		$found = null;
		$n = count($this->users);
		for ($i = 0; $i < $n; $i++){
			if($this->users[$i]->socket == $socket){
				$found = $i;
				break;
			}
		}
		if(!is_null($found)){
			array_splice($this->users, $found, 1);
		}
		$index = array_search($socket, $this->sockets);
		socket_close($socket);
		$this->console("{$socket} DISCONNECTED!");
		if($index >= 0){
			array_splice($this->sockets, $index, 1);
		}
	}
	protected function doHandshake($user, $buffer){
		$this->console("\nRequesting handshake...");
		list($resource, $host, $origin, $key, $upgrade, $con) = $this->getHeaders($buffer);
		$this->console("Handshaking...");
		$hash_data = base64_encode(sha1($key.$this->handshakeKey, true));
		$response = "HTTP/1.1 101 Switching Protocols\r\n".
					"Upgrade: {$upgrade}\r\n".
					"Connection: {$con}\r\n".
					"Sec-WebSocket-Accept: {$hash_data}\r\n".
					"\r\n";
		socket_write($user->socket, $response, strlen($response));
		$user->handshake = true;
		$this->console($response);
		$this->console("Done handshaking...");
		return true;
	}
	protected function getHeaders($req){
		$r = $h = $o = $key = null;
		if(preg_match("/GET (.*) HTTP/"   				,$req,$match)){ $r=$match[1]; }
		if(preg_match("/Host: (.*)\r\n/"  				,$req,$match)){ $h=$match[1]; }
		if(preg_match("/Origin: (.*)\r\n/"				,$req,$match)){ $o=$match[1]; }
		if(preg_match("/Sec-WebSocket-Key: (.*)\r\n/"   ,$req,$match)){ $key=$match[1]; }
		if(preg_match("/Upgrade: (.*)\r\n/"             ,$req,$match)){ $upgrade=$match[1]; }
		if(preg_match("/Connection: (.*)\r\n/"           ,$req,$match)){ $con = $match[1]; }
		return array($r, $h, $o, $key, $upgrade, $con);
	}
	protected function getUserBySocket($socket){
		$found = null;
		foreach ($this->users as $user){
			if($user->socket == $socket){
				$found = $user;
				break;
			}
		}
		return $found;
	}
	protected function ordHex($data){
		$msg = '';
		$l = strlen($data);
		for($i = 0; $i < $l; $i++){
			$msg .= dechex(ord($data{$i}));
		}
		return $msg;
	}
	protected function process($socket, $msg){
		$action = $this->unwrap($msg);
		$this->console("<".$action);
		$data = $this->beforeSend($socket, $action);
		if($data !== false){
			$this->send($socket, $data);
		}
	}
	protected function run($master){
		$changed = $this->sockets;
		$write = NULL;
		$except = NULL;
		socket_select($changed, $write, $except, NULL);
		foreach ($changed as $socket){
			if($socket == $master){
				$client = socket_accept($master);
				if($client !== false){
					$this->connect($client);
				}else{
					$this->console("socket_accept failed");
				}
			}else{
				$bytes = @socket_recv($socket, $buffer, 2048, 0);
				if($bytes != 0){
					$user = $this->getUserBySocket($socket);
					if(!$user->handshake){
						$this->doHandshake($user, $buffer);
					}else{
						$this->process($socket, $buffer);
					}
				}else{
					$this->disconnect($socket);
				}
			}
		}
	}
	protected function send($client, $msg){
		if(empty($msg)){
			return false;
		}
		$this->console(">".$msg);
		$msg = $this->wrap($msg);
		socket_write($client, $msg, strlen($msg));
// 		foreach ($this->users as $user){
// 			socket_write($user->socket, $msg, strlen($msg));
// 		}
		return true;
	}
	protected function unwrap($msg = ''){
		$mask = array();
		$data = '';
		$msg = unpack('H*', $msg);
		$head = substr($msg[1], 0, 2);
		if(hexdec($head{1}) === 8){
			$data = false;
		}elseif(hexdec($head{1}) === 1){
			$mask[] = hexdec(substr($msg[1], 4, 2));
			$mask[] = hexdec(substr($msg[1], 6, 2));
			$mask[] = hexdec(substr($msg[1], 8, 2));
			$mask[] = hexdec(substr($msg[1], 10, 2));
			$s = 12;
			$e = strlen($msg[1]) - 2;
			$n = 0;
			for($i = $s; $i <= $e; $i += 2){
				$data .= chr($mask[$n % 4]^hexdec(substr($msg[1], $i, 2)));
				$n++;
			}
		}
		return $data;
	}
	protected function wrap($msg = ''){
		$frame = array();
		$frame[0] = "81";
		$len = strlen($msg);
		$frame[1] = $len < 16 ? "0".dechex($len) : dechex($len);
		$frame[2] = $this->ordHex($msg);
		$data = implode("", $frame);
		return pack("H*", $data);
	}
}

class WebSocketUser{
	public $id;
	public $socket;
	public $handshake;
}
?>