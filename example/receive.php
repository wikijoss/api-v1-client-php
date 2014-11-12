<pre><?php

require_once('../lib/Blockchain.php');

$api_code = null;
if(file_exists('code.txt')) {
	$api_code = trim(file_get_contents('code.txt'));
}

$Blockchain = new Blockchain($api_code);

// My receive address
$destination = '1Gr6Y7ZJEmZnbDwopWKJKRTri8fBymPDfg';

// Dump the response object to the screen. ->address will forward to ->destination
var_dump($Blockchain->Receive->generate($destination));

// Output log of activity
var_dump($Blockchain->log);

?></pre>