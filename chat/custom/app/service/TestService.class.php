<?php
class TestService extends AppService{
	private $domain = "http://218.95.37.188/Pay";
	private $params = array();
	private $server = NULl;
	
	private $key = '2b3aab26e3e93de8e3e084bc';
	private $iv = '';
	private $randCode = 'abc';
	private $name = 'qiyiwang';
	private $pwd = 'qiyiwang!@#';
	private $tmpKey = '';
	
	public function __construct(){
		$this->server = curl_init($this->domain);
		$this->tmpKey = md5($this->key . $this->randCode);
		$this->addParemeter('name', $this->name);
		$this->addParemeter('ramdCode', $this->randCode);
		$this->addParemeter('encrpt', 'enAes');
		$this->addParemeter('isEncryption', 'false');
		curl_setopt($this->server, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->server, CURLOPT_USERAGENT, '6695.com API PHP5 Client 1.1 (curl) ' . phpversion());
	}
	public function getResult($method = 'post'){
		if($method == 'post'){
			curl_setopt($this->server, CURLOPT_POST, 1);
			$this->params = http_build_query($this->params);
			curl_setopt($this->server, CURLOPT_POSTFIELDS, $this->params);
		}
		$output = curl_exec($this->server);
		$info = curl_getinfo($this->server);
		curl_close($this->server);
		
		if($output === false){
			return array('status' => false, 'msg' => curl_error($this->server));
		}else{
			return array('status' => true, 'data' => $output);
		}
	}
	public function orderTicket(){
		$data = array(
				'orderId' => time(),
				'dotype' => 'orderBusTicket',
				'shengshi' => 'chongqing',
				'xmlcode' => '13774660102640000',
				'busid' => '13774660103070001',
				'mobileno' => '15922787602',
				'priceall' => '50',
				'cardtype' => 1,
				'ordername' => '周银江',
				'cardno' => '500108198601131518',
				'insurance' => 1,
				'quanpiaoCount' => 1,
				'ertongCount' => 0,
				'mianfeiCount' => 0
		);
	}
	public function queryTicket(){
		$data = array(
				'pwd' => $this->pwd,
				'busStationArea' => '重庆主城',
				'busStation' => '渝运集团北碚客运中心',
				'destination' => '荣昌',
				'shengshi' => 'chongqing',
				'date' => '2013-11-30'
		);
		$xml = $this->getRequestXML($data);
		$this->addParemeter('data', $xml);
		$this->addParemeter('mac', md5($this->tmpKey . $this->name . $xml));
		return $this->getResult();
	}
	
	private function getRequestXML($data){
		$xml = "<request>
					<password>{$data['pwd']}</password>
					<dotype>queryBusTicket</dotype>
					<shengshi>{$data['shengshi']}</shengshi>
					<busStationArea>{$data['busStationArea']}</busStationArea>
					<busStation>{$data['busStation']}</busStation>
					<destination>{$data['destination']}</destination>
					<busDepartureDate>{$data['date']}</busDepartureDate>
				</request>";
		return $xml;
	}
	private function getOrderXML($data){
		$xml = "<request>
					<password></password>
					<dotype>orderBusTicket</dotype>
					<shengshi>{$data['shengshi']}</shengshi>
					<requestflow>{$data['orderId']}</requestflow>
					<xmlcode>{$data['xmlcode']}</xmlcode>
					<busid>{$data['busid']}</busid>
					<mobileno>{$data['mobileno']}</mobileno>
					<priceall>{$data['priceall']}</priceall>
					<ticket>
						<cardtype>{$data['cardtype']}</cardtype>
						<name>{$data['ordername']}</name>
						<cardno>{$data['cardno']}</cardno>
						<insurance>{$data['insurance']}</insurance>
						<quanpiaoCount>{$data['quanpiaoCount']}</quanpiaoCount>
						<ertongCount>{$data['ertongCount']}</ertongCount>
						<mianfeiCount>{$data['mianfeiCount']}</mianfeiCount>
					</ticket>
				</request>
				";
		return $xml;
	}
	
	private function addParemeter($name, $value){
		$this->params[$name] = $value;
	}
}