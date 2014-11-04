Blockchain API library (PHP, v1)
================================

An official PHP library for interacting with the blockchain.info API.


Getting Started
---------------

Download the source or clone the repository. Copy the `lib/` folder into your project and add:
```
require('lib/Blockchain.php');

$Blockchain = new Blockchain($api_code);
```

All functionality is provided through the `Blockchain` object. If you need an API code, you may request one [here](https://blockchain.info/api/api_create_code).


Documentation
-------------

[Block explorer](docs/blockexplorer.md)