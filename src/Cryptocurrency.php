<?php

namespace Samirdjelal\Cryptocurrency;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class Cryptocurrency
{
	protected $client;
	// private $currency;
	private $orderId;

	public function __construct()
	{
		$this->client = new GuzzleClient();
	}

	/**
	 * info: To be used when other we add more currencies.
	 * @return $this
	 */
	public function bitcoin()
	{
		// $this->currency = 'bitcoin';
		return $this;
	}

	public function price()
	{
		try {
			$response = $this->client->request('GET', 'https://blockchain.info/ticker', [
				'headers' => ['Accepts' => 'application/json']
			]);
			return \GuzzleHttp\json_decode($response->getBody()->getContents())->USD->last;
		} catch (GuzzleException $e) {
			return 'failed to fetch the price.';
		}

	}

	public function orderId(string $orderId = '')
	{
		$this->orderId = $orderId;
		return $this;
	}

	public function address()
	{
		try {
			 $callback = url("/cryptocurrency/callback/?invoice=" . $this->orderId . "&secret=" . config('cryptocurrency.secret_key'));
//			$callback = 'https://laravel7.sharedwithexpose.com/cryptocurrency/callback/?invoice=' . urlencode($this->orderId) . '&secret=' . urlencode(config('cryptocurrency.callback_secret_key'));
			$response = $this->client->request('GET',
				'https://api.blockchain.info/v2/receive?key=' . config('cryptocurrency.blockchain_api_key') . '&xpub=' . config('cryptocurrency.blockchain_xpub') . '&callback=' . urlencode($callback));

			$result = \GuzzleHttp\json_decode($response->getBody()->getContents());
			return [
				'status' => true,
				'address' => $result->address,
				'callback' => $result->callback,
			];
		} catch (GuzzleException $e) {
			return [
				'status' => false,
				'message' => 'failed to fetch the address.',
			];
		}
	}




}
