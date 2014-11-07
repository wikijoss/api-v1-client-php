<?php

class Wallet {
	private $identifier = null;
	private $main_password = null;
	private $second_password = null;

	public function __construct(Blockchain $blockchain) {
		$this->blockchain = $blockchain;
	}

	public function credentials($id, $pw1, $pw2=null) {
		$this->identifier = $id;
		$this->main_password = $pw1;
		if(!is_null($pw2)) {
			$this->second_password = $pw2;
		}
	}

	private function _checkCredentials() {
		if(is_null($this->identifier) || is_null($this->main_password)) {
			throw new Blockchain_CredentialsError('Please enter wallet credentials.');
		}
	}

    private function reqParams($extras=array()) {
        $ret = array('password'=>$this->main_password);
        if(!is_null($this->second_password)) {
            $ret['second_password'] = $this->second_password;
        }

        return array_merge($ret, $extras);
    }

    private function url($resource) {
        return "merchant/" . $this->identifier . "/" . $resource;
    }

    private function call($resource, $params=array()) {
        $this->_checkCredentials();
        return $this->blockchain->post($this->url($resource), $this->reqParams($params)); 
    }

    public function getIdentifier() {
        return $this->identifier;
    }

    public function getBalance() {
        $json = $this->call('balance');
        return BTC_int2str($json['balance']);
    }

    public function getAddresses() {
        $json = $this->call('list');
        $addresses = array();
        foreach ($json['addresses'] as $address) {
            $addresses[] = new WalletAddress($address);
        }
        return $addresses;
    }

}

class PaymentResponse {
    public $message;                    //
    public $tx_hash;
    public $notice;

    public function __construct($json) {
        if(array_key_exists('message', $json))
            $this->message = $json['message'];
        if(array_key_exists('tx_hash', $json))
            $this->tx_hash = $json['tx_hash'];
        if(array_key_exists('notice', $json))
            $this->notice = $json['notice'];
    }
}

class WalletAddress {
    public $balance;                    // string
    public $address;                    // string
    public $label;                      // string
    public $total_received;             // string

    public function __construct($json) {
        if(array_key_exists('balance', $json))
            $this->balance = BTC_int2str($json['balance']);
        if(array_key_exists('address', $json))
            $this->address = $json['address'];
        if(array_key_exists('label', $json))
            $this->label = $json['label'];
        if(array_key_exists('total_received', $json))
            $this->total_received = $json['total_received'];
    }
}