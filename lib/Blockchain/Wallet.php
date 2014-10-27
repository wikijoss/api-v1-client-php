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

}