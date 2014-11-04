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
$block = $Blockchain->Explorer->getBlock($hash);
```