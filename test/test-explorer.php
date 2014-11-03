<?php

require_once('../lib/Blockchain.php');

$api_code = null;
if(file_exists('code.txt')) {
	$api_code = trim(file_get_contents('code.txt'));
}

$Blockchain = new Blockchain($api_code);

// List all blocks at a certain height
print_r($Blockchain->Explorer->getBlocksAtHeight(1));

// Get block by index
$block = $Blockchain->Explorer->getBlockAtIndex(100000);
print_r($block);

// Get previous block by hash
$hash = $block->previous_block;
print_r($Blockchain->Explorer->getBlock($hash));

// First mining reward transaction
print_r($Blockchain->Explorer->getTransaction('0e3e2357e806b6cdb1f70b54c3a3a17b6714ee1f0e68bebb44a74b1efd512098'));

// Get details of a single address
print_r($Blockchain->Explorer->getAddress('1AqC4PhwYf7QAyGBhThcyQCKHJyyyLyAwc'));

// Get unspent outputs for addresses
print_r($Blockchain->Explorer->getUnspentOutputs(array('1AqC4PhwYf7QAyGBhThcyQCKHJyyyLyAwc', '1PfcDu4n11Dv7rNexM1AxrNWqkEgwCvYWD')));

// Output log of activity
print_r($Blockchain->log);

