<?php

return [

	// Blockchain API Key
	'blockchain_api_key' => env('BLOCKCHAIN_API_KEY', ''),

	// Blockchain xPub key (located in Settings -> Wallets & Addresses -> Manage -> More Options -> Show xPub)
	'blockchain_xpub' => env('BLOCKCHAIN_XPUB', ''),

	// secret key to protect the from false submits.
	'callback_secret_key' => env('CALLBACK_SECRET_KEY', ''),

	// Blockchain websocket, do not change it.
	'blockchain_websocket' => 'wss://ws.blockchain.info/inv',

];
