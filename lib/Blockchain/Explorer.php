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
}