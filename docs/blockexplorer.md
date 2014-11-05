Block Explorer Documentation
============================

The Blockchain block explorer provides programmatic access to the Bitcoin network internals.


Usage
-----

Include the core Blockchain php file for all client API operations.

```
require('lib/Blockchain.php');
```

Create a new Blockchain object, with optional API code. Request an API code [here](https://blockchain.info/api/api_create_code).

```
$Blockchain = new Blockchain($api_code);
```

All block explorer functionality is available from the `Explorer` member object within `$Blockchain`:

```
$block = $Blockchain->Explorer->getTransaction($tx_hash);
```


###Blocks

Blocks may be queried in multiple ways: by the block hash, by the height in the blockchain, or by the block's index. Calls return `Block` objects. `getBlocksAtHeight` returns an array of `Block` objects.

```
$block = $Blockchain->Explorer->getBlock($hash);
$blocks = $Blockchain->Explorer->getBlocksAtHeight($height_int);
$block = $Blockchain->Explorer->getBlockByIndex($index_int);
```


###Transactions
Get a single transaction based on hash or index. Returns a `Transaction` object.

```
$tx = $Blockchain->Explorer->getTransaction($hash);
$tx = $Blockchain->Explorer->getTransactionByIndex($index);
```


###Addresses
Get the details of an address, including a paged list of transactions. Returns an `Address` object.

```
$limit = 50;
$offset = 0;
$address = $Blockchain->Explorer->getAddress($address, $limit, $offset);
```


###Unspent Outputs
Get an array of `UnspentOutput` objects for a given address.

```
$unspent = $Blockchain->Explorer->getUnspentOutputs($address);
```


###Latest Block
Get the latest block on the main chain. Returns a simpler `LatestBlock` object;

```
$latest = $Blockchain->Explorer->getLatestBlock();
```


###Unconfirmed Transactions
Get a list of unconfirmed transactions. Returns an array of `Transaction` objects.

```
$unconfirmed = $Blockchain->Explorer->getUnconfirmedTransactions();
```


###Simple Blocks
Get blocks from a particular day or from a given mining pool. Return arrays of `SimpleBlock` objects.

```
$simple_blocks = $Blockchain->Explorer->getBlocksForDay($int_time);
$simple_blocks = $Blockchain->Explorer->getBlocksByPool($pool_name);
```
For a list of mining pool names, visit [this page](https://blockchain.info/pools).


###Inventory Data
Gets data for recent blockchain entities, up to one hour old. Returns an `InventoryData` object.

```
$data = $Blockchain->Explorer->getInventoryData($hash);
```


Return Objects
--------------

Calls to the API return first-class objects.

###Block

```
class Block {
    public $hash;                       // string
    public $version;                    // int
    public $previous_block;             // string
    public $merkle_root;                // string
    public $time;                       // int
    public $bits;                       // int
    public $fee;                        // int
    public $nonce;                      // int
    public $n_tx;                       // int
    public $size;                       // int
    public $block_index;                // int
    public $main_chain;                 // bool
    public $height;                     // int
    public $received_time;              // int
    public $relayed_by;                 // string
    public $transactions = array();     // Array of Transaction objects
}
```

###Transaction
```
class Transaction {
    public $double_spend = false;       // bool
    public $block_height;               // int
    public $time;                       // int
    public $lock_time;                  // int
    public $relayed_by;                 // string
    public $hash;                       // string
    public $tx_index;                   // int
    public $version;                    // int
    public $size;                       // int
    public $inputs = Array();           // Array of Input objects
    public $outputs = Array();          // Array of Output objects
}
```

### Input
```
class Input {
    public $sequence;                   // int
    public $script_sig;                 // string
    public $coinbase = true;            // bool
    // If coinbase is false, then the following fields are created
    public $n;                          // int
    public $value;                      // int
    public $address;                    // string
    public $tx_index;                   // int
    public $type;                       // int
    public $script;                     // string
    public $address_tag;                // string
    public $address_tag_link;           // string
}
```

### Output
```
class Output {
    public $n;                          // int
    public $value;                      // int
    public $address;                    // string
    public $tx_index;                   // int
    public $script;                     // string
    public $spent;                      // bool
}
```

### Address
```
class Address {
    public $hash160;                    // string
    public $address;                    // string
    public $n_tx;                       // int
    public $total_received;             // int
    public $total_sent;                 // int
    public $final_balance;              // int
    public $transactions = array();     // Array of Transaction objects
}
```

### UnspentOutput
```
class UnspentOutput {
    public $tx_hash;                    // string
    public $tx_index;                   // int
    public $tx_output_n;                // int
    public $script;                     // string
    public $value;                      // int
    public $value_hex;                  // string
    public $confirmations;              // int
}
```

### LatestBlock
```
class LatestBlock {
    public $hash;                       // string
    public $time;                       // int
    public $block_index;                // int
    public $height;                     // int
    public $tx_indexes = array();       // Array of integer transaction indexes
}
```

### SimpleBlock
```
class SimpleBlock {
    public $height;                     // int
    public $hash;                       // string
    public $time;                       // int
    public $main_chain;                 // bool
}
```

### InventoryData
```
class InventoryData {
    public $hash;                       // string
    public $type;                       // string
    public $initial_time;               // int
    public $initial_ip;                 // string
    public $nconnected;                 // int
    public $relayed_count;              // int
    public $relayed_percent;            // int
}
```