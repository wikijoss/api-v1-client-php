Wallet Documetation
===================
Access a Blockchain wallet programmatically. Offical documentation [here](https://blockchain.info/api/blockchain_wallet_api).

Basic Usage
-----------
The `Blockchain` object contains a single member `Wallet` object. The wallet credentials must be set before any functionality may be used. Accessing multiple wallets is as simple as setting the credentials before making wallet calls.

```
$Blockchain = new Blockchain($api_code);
$Blockchain->Wallet->credentials('wallet-id-1', 'password-1', 'optional 2nd password');

// Operations on "wallet-id-1"
// ...

// Switch to another wallet
$Blockchain->Wallet->credentials('wallet-id-2', 'password-2', 'optional 2nd password');

// Operations on "wallet-id-2"
// ...
```


###Get Current Identifier
Use the `getIdentifier` function to check which wallet is active, without having to enter additional credentials. Returns a string.

```
$active_id = $Blockchain->Wallet->getIdentifier();
```


Balances
--------
Functions for fetching the balance of a whole wallet or of a particular address.


###Wallet Balance
Get the balance of the whole wallet. Returns a `string` representing the floating point balance, e.g. `"12.64952835"`.

```
$balance = $Blockchain->Wallet->getBalance();
```


###Address Balance
Get the balance of a single wallet address. Returns a `WalletAddress` object.

```
$balance = $Blockchain->Wallet->getAddressBalance($address);
```


Transactions
------------
Functions for making outgoing Bitcoin transactions from the wallet.


Address Management
------------------
A wallet may contain many addresses, not all of which must be active at all times. Active addresses are monitored for activity, while archived addresses are not. It is recommended that addresses be archived when it is reasonable to assume that there will not be further activity to that address. For instance, after an invoice is filled, the payment address may be archived once the received coins are moved.


###List Active Addresses
Call `getAddresses` to return a list of the active addresses within the wallet. Returns an array of `WalletAddress` objects.

```
$addresses = $Blockchain->Wallet->getAddresses();
```


###Get New Address
Generate a new Bitcoin address, with an optional label, less than 255 characters in length. Returns a `WalletAddress` object.

```
$address = $Blockchain->Wallet->getNewAddress($label=null);
```


###Archive Address
Move an address to the archive. Returns `true` on success and `false` on failure.

```
$address = $Blockchain->Wallet->archiveAddress($address);
```


Return Objects
--------------

Calls to the API usually return first-class objects.

###WalletAddress

```
class WalletAddress {
    public $balance;                    // string, e.g. "12.64952835"
    public $address;                    // string
    public $label;                      // string
    public $total_received;             // string, e.g. "12.64952835"
}
```

