<?php

namespace Samirdjelal\Cryptocurrency;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Samirdjelal\Cryptocurrency\Models\Cryptocurrency as CryptocurrencyModel;

class Cryptocurrency
{
	protected $client;
	// private $currency;
	protected $orderId;

	public function __construct()
	{
		$this->client = new GuzzleClient([
			'verify' => false,
			'http_errors' => false,
			'decode_content' => false
		]);
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

	/**
	 * Get the current price of 1 BTC in USD.
	 * @param bool $returnJson
	 * @return array
	 */
	public function price($returnJson = true)
	{
		try {
			$response = $this->client->request('GET', 'https://blockchain.info/ticker', [
				'headers' => ['Accepts' => 'application/json']
			]);
			$result = \GuzzleHttp\json_decode($response->getBody()->getContents());
			if ($returnJson == false) return $result->USD->last;
			return [
				'status' => true,
				'usd' => $result->USD->last,
				'eur' => $result->EUR->last,
				'gbp' => $result->GBP->last,
			];
		} catch (GuzzleException $e) {
			if ($returnJson == false) return false;
			return [
				'status' => false,
				'message' => 'failed to fetch the price.',
			];
		}

	}

	public function orderId(string $orderId = '')
	{
		$this->orderId = $orderId;
		return $this;
	}

	public function address()
	{
		if (empty($this->orderId)) return [
			'status' => false,
			'message' => 'orderId() is missing!',
		];

		try {
			// $callback = url("/cryptocurrency/callback/?invoice=" . $this->orderId . "&secret=" . config('cryptocurrency.secret_key'));
			$callback = 'https://laravel7.sharedwithexpose.com/cryptocurrency/callback/' . $this->orderId . '/' . config('cryptocurrency.callback_secret_key');

			/**
			 * check if the order is still valid.
			 */
			$order = CryptocurrencyModel::where('order_id', '=', $this->orderId)->first();
			if ($order) {
				return [
					'status' => true,
					'address' => $order->address,
					'callback' => $order->callback,
				];
			}

			$response = $this->client->request('GET',
				'https://api.blockchain.info/v2/receive?key=' . config('cryptocurrency.blockchain_api_key') .
				'&xpub=' . config('cryptocurrency.blockchain_xpub') .
				'&callback=' . urlencode($callback) .
				'&gap_limit=' . config('cryptocurrency.gap_limit'));

			$r = \GuzzleHttp\json_decode($response->getBody()->getContents());

			if ($response->getStatusCode() == 200) {

				CryptocurrencyModel::create([
					'order_id' => $this->orderId,
					'address' => $r->address,
					'callback' => $callback
				]);

				return [
					'status' => true,
					'address' => $r->address,
					'callback' => $r->callback,
				];
			}

		} catch (GuzzleException $e) {
		}

		/**
		 * re-use the first unused address instead of return an error.
		 */

		$oldOrder = CryptocurrencyModel::where('status', '=', 'waiting')->first();
		if ($oldOrder) {
			$newOrder = CryptocurrencyModel::create([
				'order_id' => $this->orderId,
				'address' => $oldOrder->address,
				'callback' => $callback
			]);
			$oldOrder->delete();
			return [
				'status' => true,
				'address' => $newOrder->address,
				'callback' => $newOrder->callback,
			];
		}
		return [
			'status' => false,
			'message' => 'failed to fetch an address.',
		];
	}


	/**
	 * Check a specific orderId status, and get the total value received in the cryptocurrency address
	 * @return array|bool[]
	 */
	public function check()
	{
		if (empty($this->orderId)) return [
			'status' => false,
			'message' => 'orderId() is missing!',
		];
		try {
			$order = CryptocurrencyModel::where('order_id', '=', $this->orderId)->first();
			if ($order) {
				$response = $this->client->request('GET', 'https://blockchain.info/rawaddr/' . $order->address, [
					'headers' => ['Accepts' => 'application/json']
				]);
				$result = \GuzzleHttp\json_decode($response->getBody()->getContents());
				ddd($result);
				ddd($this->btcToUsd($result->total_received));
//				return [
//					'status' => true,
//					'callback' => \GuzzleHttp\json_decode($response->getBody()->getContents())->gap
//				];
			}
		} catch (GuzzleException $e) {
		}
		return ['status' => false];
	}


	public function callback()
	{
		if (empty($this->orderId)) return [
			'status' => false,
			'message' => 'orderId() is missing!',
		];
		try {
			$order = CryptocurrencyModel::where('order_id', '=', $this->orderId)->first();
			if ($order) {
				$response = $this->client->request('GET', 'https://api.blockchain.info/v2/receive/callback_log?callback=' . urlencode($order->callback) . '&key=' . config('cryptocurrency.blockchain_api_key'), [
					'headers' => ['Accepts' => 'application/json']
				]);
				// ddd($response->getBody()->getContents());
				return [
					'status' => true,
					'callback' => \GuzzleHttp\json_decode($response->getBody()->getContents())
				];
			}
		} catch (GuzzleException $e) {
		}
		return ['status' => false];
	}

	public function btcToUsd($value=0)
	{
		return round((($value / 100000000) * $this->price(false)), 2);
	}

	/**
	 * The gap between the last used address and the last generated address.
	 * @return array|bool[]
	 */
	public function gap()
	{
		try {
			$response = $this->client->request('GET', 'https://api.blockchain.info/v2/receive/checkgap?xpub=' . config('cryptocurrency.blockchain_xpub') . '&key=' . config('cryptocurrency.blockchain_api_key'), [
				'headers' => ['Accepts' => 'application/json']
			]);
			return [
				'status' => true,
				'gap' => \GuzzleHttp\json_decode($response->getBody()->getContents())->gap
			];
		} catch (GuzzleException $e) {
		}
		return ['status' => false];
	}

}
