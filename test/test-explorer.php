<?php

require_once('../lib/Blockchain.php');

$api_code = null;
if(file_exists('code.txt')) {
	$api_code = trim(file_get_contents('code.txt'));
}

$Blockchain = new Blockchain($api_code);

// List all blocks at a certain height
print_r($Blockchain->Explorer->getBlocksAtHeight(23));

// Get block by index
$block = $Blockchain->Explorer->getBlockAtIndex(100000);
print_r($block);

// Get previous block by hash
$hash = $block['prev_block'];
print_r($Blockchain->Explorer->getBlock($hash));

// First mining reward transaction
print_r($Blockchain->Explorer->getTransaction('4a5e1e4baab89f3a32518a88c31bc87f618f76673e2cc77ab2127b7afdeda33b'));


// Output log of activity
print_r($Blockchain->log);