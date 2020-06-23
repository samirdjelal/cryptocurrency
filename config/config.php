<?php

return [

	/**
	 * Blockchain API Key
	 */

	'blockchain_api_key' => env('BLOCKCHAIN_API_KEY', ''),


	/**
	 * Blockchain xPub key (located in Settings -> Wallets & Addresses -> Manage -> More Options -> Show xPub)
	 */

	'blockchain_xpub' => env('BLOCKCHAIN_XPUB', ''),


	/**
	 * Secret key to protect the from false submits.
	 */

	'callback_secret_key' => env('CALLBACK_SECRET_KEY', ''),


	/**
	 * Blockchain websocket, do not change it.
	 */

	'blockchain_websocket' => 'wss://ws.blockchain.info/inv',


	/**
	 * Number of unused addresses that can be generated before reaching the limit.
	 * Setting the  gap_limit to more than 20, This might make funds inaccessible.
	 */

	'gap_limit' => 20,

];
