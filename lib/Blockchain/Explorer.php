<?php

class Explorer {
	public function __construct(Blockchain $blockchain) {
		$this->blockchain = $blockchain;
	}

	public function getBlock($hash) {
		return $this->blockchain->get('rawblock/' . $hash, array('format'=>'json'));
	}

	public function getBlocksAtHeight($height) {
		if(!is_integer($height)) {
			throw new Blockchain_FormatError('Block height must be iteger.');
		}
		return $this->blockchain->get('block-height/' . $height, array('format'=>'json'));
	}

	public function getBlockAtIndex($index) {
		if(!is_integer($index)) {
			throw new Blockchain_FormatError('Block index must be iteger.');
		}
		return $this->blockchain->get('block-index/' . $index, array('format'=>'json'));
	}

	public function getTransaction($txid) {
		return new Transaction($this->blockchain->get('rawtx/' . $txid, array('format'=>'json')));
	}
}

class Input {
	public $sequence;
	public $script_sig;
	public $coinbase = true;

    public function __construct($json) {
    	if(array_key_exists('sequence', $json))
    		$this->sequence = $json['sequence'];
    	if(array_key_exists('script', $json))
    		$this->script_sig = $json['script'];

    	if(array_key_exists('prev_out', $json)) {
    		$this->coinbase = false;

    		$P = $json['prev_out'];
    		if(array_key_exists('n', $P)) 
	    		$this->n = $P['n'];
    		if(array_key_exists('value', $P)) 
			    $this->value = $P['value'];
    		if(array_key_exists('addr', $P)) 
			    $this->address = $P['addr'];
    		if(array_key_exists('tx_index', $P)) 
			    $this->tx_index = $P['tx_index'];
    		if(array_key_exists('type', $P)) 
			    $this->type = $P['type'];
    		if(array_key_exists('script', $P)) 
			    $this->script = $P['script'];
    	}
    }
}

class Output {
	public $n;
    public $value;
    public $address;
    public $tx_index;
    public $script;
    public $spent;

    public function __construct($json) {
    	if(array_key_exists('n', $json))
    		$this->n = $json['n'];
    	if(array_key_exists('value', $json))
    		$this->value = $json['value'];
    	if(array_key_exists('addr', $json))
    		$this->address = $json['addr'];
    	if(array_key_exists('tx_index', $json))
    		$this->tx_index = $json['tx_index'];
    	if(array_key_exists('script', $json))
    		$this->script = $json['script'];
    	if(array_key_exists('spent', $json))
    		$this->spent = $json['spent'];
    }
}

class Transaction {
	public $double_spend = false;
    public $block_height;
    public $time;
    public $lock_time;
    public $relayed_by;
    public $hash;
    public $tx_index;
    public $version;
    public $size;
    public $inputs = Array();
    public $outputs = Array();

    public function __construct($json) {
    	if(array_key_exists('double_spend', $json))
    		$this->double_spend = $json['double_spend'];
    	if(array_key_exists('block_height', $json))
    		$this->block_height = $json['block_height'];
    	if(array_key_exists('time', $json))
    		$this->time = $json['time'];
    	if(array_key_exists('lock_time', $json))
    		$this->lock_time = $json['lock_time'];
    	if(array_key_exists('relayed_by', $json))
    		$this->relayed_by = $json['relayed_by'];
    	if(array_key_exists('hash', $json))
    		$this->hash = $json['hash'];
    	if(array_key_exists('tx_index', $json))
    		$this->tx_index = $json['tx_index'];
    	if(array_key_exists('ver', $json))
    		$this->version = $json['ver'];
    	if(array_key_exists('size', $json))
    		$this->size = $json['size'];
    	if(array_key_exists('inputs', $json)) {
    		foreach ($json['inputs'] as $input) {
    			$this->inputs[] = new Input($input);
    		}
    	}
    	if(array_key_exists('out', $json)) {
    		foreach ($json['out'] as $output) {
    			$this->outputs[] = new Output($output);
    		}
    	}
    }
}

class Block {
	public $hash;
    public $version;
    public $previous_block;
    public $merkle_root;
    public $time;
    public $bits;
    public $fee;
    public $nonce;
    public $n_tx;
    public $size;
    public $block_index;
    public $main_chain;
    public $height;
    public $received_time;
    public $relayed_by;
    public $transactions;

    public function __construct($json) {

    }
}