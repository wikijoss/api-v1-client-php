<?php

require_once('../lib/Blockchain.php');

$api_code = null;
if(file_exists('code.txt')) {
	$api_code = trim(file_get_contents('code.txt'));
}

$Blockchain = new Blockchain($api_code);

$blocks23 = $Blockchain->Explorer->getBlocksAtHeight(23);
print_r($blocks23);

?>