<?php

class Explorer {
	public function __construct(Blockchain $blockchain) {
		$this->blockchain = $blockchain;
	}

	public function getBlock($hash) {
		return new Block($this->blockchain->get('rawblock/' . $hash, array('format'=>'json')));
	}

	public function getBlocksAtHeight($height) {
		if(!is_integer($height)) {
			throw new Blockchain_FormatError('Block height must be iteger.');
		}
		$blocks = array();
		$json = $this->blockchain->get('block-height/' . $height, array('format'=>'json'));
		if(array_key_exists('blocks', $json)) {
			foreach ($json['blocks'] as $block) {
				$blocks[] = new Block($block);
			}
		}

		return $blocks;
	}

	public function getBlockAtIndex($index) {
		if(!is_integer($index)) {
			throw new Blockchain_FormatError('Block index must be iteger.');
		}
		return new Block($this->blockchain->get('block-index/' . $index, array('format'=>'json')));
	}

	public function getTransaction($txid) {
		return new Transaction($this->blockchain->get('rawtx/' . $txid, array('format'=>'json')));
	}

	/* 	Get details about a single address, listing up to $limit transactions
	 	starting at $offset.
	*/
	public function getAddress($address, $limit=50, $offset=0) {
		$params = array(
			'format'=>'json',
			'limit'=>intval($limit),
			'offset'=>intval($offset)
		);
		return new Address($this->blockchain->get('address/' . $address, $params));
	}

	/* Get a list of unspent outputs for an array of addresses

	*/
	public function getUnspentOutputs($addresses) {
		if(!is_array($addresses))
			throw new Blockchain_FormatError('Must pass array argument.');
		$params = array(
			'format'=>'json',
			'active'=>implode('|', $addresses)
		);
		$json = $this->blockchain->get('unspent', $params);
		$outputs = Array();
		if(array_key_exists('unspent_outputs', $json)) {
			foreach ($json['unspent_outputs'] as $output) {
				$outputs[] = new UnspentOutput($output);
			}
		}
		return $outputs;
	}

	public function getLatestBlock() {
		return new LatestBlock($this->blockchain->get('latestblock', array('format'=>'json')));
	}

	public function getUnconfirmedTransactions() {
		$json = $this->blockchain->get('unconfirmed-transactions', array('format'=>'json'));
		$txn = array();
		if(array_key_exists('txs', $json)) {
			foreach ($json['txs'] as $tx) {
				$txn[] = new Transaction($tx);
			}
		}
		return $txn;
	}

	/* Get blocks for a specific day, provided UNIX timestamp, in seconds.
	*/
	public function getBlocksForDay($unix_time=0) {
		$time_ms = strval($unix_time) . '000';
		return $this->processSimpleBlockJSON($this->blockchain->get('blocks/'.$time_ms, array('format'=>'json')));
	}

	/* Get blocks for a specific mining pool.
	*/
	public function getBlocksByPool($pool) {
		return $this->processSimpleBlockJSON($this->blockchain->get('blocks/'.$pool, array('format'=>'json')));
	}

	private function processSimpleBlockJSON($json) {
		$blocks = array();
		if(array_key_exists('blocks', $json)) {
			foreach ($json['blocks'] as $block) {
				$blocks[] = new SimpleBlock($block);
			}
		}
		return $blocks;
	}

	public function getInventoryData($hash) {
		return new InventoryData($this->blockchain->get('inv/'.$hash, array('format'=>'json')));
	}
}

class InventoryData {
    public $hash;
    public $type;
    public $initial_time;
    public $initial_ip;
    public $nconnected;
    public $relayed_count;
    public $relayed_percent;

    public function __construct($json) {
    	if(array_key_exists('hash', $json))
    		$this->hash = $json['hash'];
    	if(array_key_exists('type', $json))
    		$this->type = $json['type'];
    	if(array_key_exists('initial_time', $json))
    		$this->initial_time = $json['initial_time'];
    	if(array_key_exists('initial_ip', $json))
    		$this->initial_ip = $json['initial_ip'];
    	if(array_key_exists('nconnected', $json))
    		$this->nconnected = $json['nconnected'];
    	if(array_key_exists('relayed_count', $json))
    		$this->relayed_count = $json['relayed_count'];
    	if(array_key_exists('relayed_percen', $json))
    		$this->relayed_percen = $json['relayed_percen'];
    }
}

class SimpleBlock {
    public $height;
    public $hash;
    public $time;
    public $main_chain;

    public function __construct($json) {
    	if(array_key_exists('height', $json))
    		$this->height = $json['height'];
    	if(array_key_exists('hash', $json))
    		$this->hash = $json['hash'];
    	if(array_key_exists('time', $json))
    		$this->time = $json['time'];
    	if(array_key_exists('main_chain', $json))
    		$this->main_chain = $json['main_chain'];
    }
}
        
class LatestBlock {
    public $hash;
    public $time;
    public $block_index;
    public $height;
    public $tx_indexes = array();

    public function __construct($json) {
    	if(array_key_exists('hash', $json))
    		$this->hash = $json['hash'];
    	if(array_key_exists('time', $json))
    		$this->time = $json['time'];
    	if(array_key_exists('block_index', $json))
    		$this->block_index = $json['block_index'];
    	if(array_key_exists('height', $json))
    		$this->height = $json['height'];
    	if(array_key_exists('txIndexes', $json))
			$this->tx_indexes[] = $json['txIndexes'];
    }
}

class Address {
	public $hash160;
    public $address;
    public $n_tx;
    public $total_received;
    public $total_sent;
    public $final_balance;
    public $transactions = array();

    public function __construct($json) {
    	if(array_key_exists('hash160', $json))
    		$this->hash160 = $json['hash160'];
    	if(array_key_exists('address', $json))
    		$this->address = $json['address'];
    	if(array_key_exists('n_tx', $json))
    		$this->n_tx = $json['n_tx'];
    	if(array_key_exists('total_received', $json))
    		$this->total_received = $json['total_received'];
    	if(array_key_exists('total_sent', $json))
    		$this->total_sent = $json['total_sent'];
    	if(array_key_exists('final_balance', $json))
    		$this->final_balance = $json['final_balance'];
    	if(array_key_exists('txs', $json)) {
    		foreach ($json['txs'] as $txn) {
    			$this->transactions[] = new Transaction($txn);
    		}
    	}
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
			if(array_key_exists('addr_tag', $P))
	    		$this->address_tag = $P['addr_tag'];
	    	if(array_key_exists('addr_tag_link', $P))
	    		$this->address_tag_link = $P['addr_tag_link'];
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
    	if(array_key_exists('addr_tag', $json))
    		$this->address_tag = $json['addr_tag'];
    	if(array_key_exists('addr_tag_link', $json))
    		$this->address_tag_link = $json['addr_tag_link'];
    }
}

class UnspentOutput {
	public $tx_hash;
    public $tx_index;
    public $tx_output_n;
    public $script;
    public $value;
    public $value_hex;
    public $confirmations;

    public function __construct($json) {
    	if(array_key_exists('tx_hash', $json))
    		$this->tx_hash = $json['tx_hash'];
    	if(array_key_exists('tx_index', $json))
    		$this->tx_index = $json['tx_index'];
    	if(array_key_exists('tx_output_n', $json))
    		$this->tx_output_n = $json['tx_output_n'];
    	if(array_key_exists('script', $json))
    		$this->script = $json['script'];
    	if(array_key_exists('value', $json))
    		$this->value = $json['value'];
    	if(array_key_exists('value_hex', $json))
    		$this->value_hex = $json['value_hex'];
    	if(array_key_exists('confirmations', $json))
    		$this->confirmations = $json['confirmations'];
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
    public $transactions = array();

    public function __construct($json) {
    	if(array_key_exists('hash', $json))
    		$this->hash = $json['hash'];
    	if(array_key_exists('ver', $json))
    		$this->version = $json['ver'];
    	if(array_key_exists('prev_block', $json))
    		$this->previous_block = $json['prev_block'];
    	if(array_key_exists('mrkl_root', $json))
    		$this->merkle_root = $json['mrkl_root'];
    	if(array_key_exists('time', $json))
    		$this->time = $json['time'];
    	if(array_key_exists('bits', $json))
    		$this->bits = $json['bits'];
    	if(array_key_exists('fee', $json))
    		$this->fee = $json['fee'];
    	if(array_key_exists('nonce', $json))
    		$this->nonce = $json['nonce'];
    	if(array_key_exists('n_tx', $json))
    		$this->n_tx = $json['n_tx'];
    	if(array_key_exists('size', $json))
    		$this->size = $json['size'];
    	if(array_key_exists('block_index', $json))
    		$this->block_index = $json['block_index'];
    	if(array_key_exists('main_chain', $json))
    		$this->main_chain = $json['main_chain'];
    	if(array_key_exists('height', $json))
    		$this->height = $json['height'];
    	if(array_key_exists('received_time', $json))
    		$this->received_time = $json['received_time'];
    	if(array_key_exists('relayed_by', $json))
    		$this->relayed_by = $json['relayed_by'];
    	if(array_key_exists('tx', $json)) {
    		foreach ($json['tx'] as $tx) {
    			$this->transactions[] = new Transaction($tx);
    		}
    	}
    }
}