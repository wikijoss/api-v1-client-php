<?php

require_once(__DIR__.'/Blockchain/Exceptions.php');
require_once(__DIR__.'/Blockchain/Explorer.php');

class Blockchain {
	const GET_URL = 'https://blockchain.info/';
	const API_URL = 'https://blockchain.info/api/';


	private $ch;
	private $api_code = null;

	const DEBUG = true;
	public $log = Array();

	public function __construct($api_code=null) {
		if(!is_null($api_code)) {
			$this->api_code = $api_code;
		}

		$this->ch = curl_init();
		curl_setopt($this->ch, CURLOPT_USERAGENT, 'Blockchain-PHP/1.0');
        curl_setopt($this->ch, CURLOPT_HEADER, false);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 600);
        curl_setopt($this->ch, CURLOPT_CAINFO, __DIR__.'/Blockchain/ca-bundle.crt');

        $this->Explorer = new Explorer($this);
	}

	public function __deconstruct() {
		curl_close($this->ch);
	}

	public function setTimeout($timeout) {
		curl_setopt($this->ch, CURLOPT_TIMEOUT, intval($timeout));
	}

	public function post($resource, $data=null) {
		curl_setopt($this->ch, CURLOPT_URL, self::API_URL.$resource);
		curl_setopt($this->ch, CURLOPT_POST, true);

		if(!is_null($this->api_code)) {
			$data['api_code'] = $this->api_code;
		}

		$json = $this->_call();

		// throw ApiError if we get an 'error' field in the JSON
		if(array_key_exists('error', $json)) {
			throw new Blockchain_ApiError($json['error']);
		}

		return $json;
	}

	public function get($resource, $params=null) {
		curl_setopt($this->ch, CURLOPT_POST, false);

		if(!is_null($this->api_code)) {
			$params['api_code'] = $this->api_code;
		}
		$query = http_build_query($params);
		curl_setopt($this->ch, CURLOPT_URL, self::GET_URL.$resource.'?'.$query);

		return $this->_call();
	}

	private function _call() {
		$t0 = microtime(true);
		$response = curl_exec($this->ch);
		$dt = microtime(true) - $t0;

		if(curl_error($this->ch)) {
			$info = curl_getinfo($this->ch);
			throw new Blockchain_HttpError("Call to " . $info['url'] . " failed: " . curl_error($this->ch));
		}
		$json = json_decode($response, true);
		if(is_null($json)) {
			throw new Blockchain_Error("Unable to decode JSON response from Blockchain: " . $response);
		}

		if(self::DEBUG) {
			$info = curl_getinfo($this->ch);
			$this->log[] = array(
				'curl_info' => $info,
				'elapsed_ms' => round(1000*$dt)
			);
		}

		return $json;
	}
}
