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

	public function price()
	{
		try {
			$response = $this->client->request('GET', 'https://blockchain.info/ticker', [
				'headers' => ['Accepts' => 'application/json']
			]);
			$result = \GuzzleHttp\json_decode($response->getBody()->getContents());
			return [
				'status' => true,
				'usd' => $result->USD->last,
				'aud' => $result->AUD->last,
				'brl' => $result->BRL->last,
				'cad' => $result->CAD->last,
				'chf' => $result->CHF->last,
				'clp' => $result->CLP->last,
				'cny' => $result->CNY->last,
				'dkk' => $result->DKK->last,
				'eur' => $result->EUR->last,
				'gbp' => $result->GBP->last,
				'hkd' => $result->HKD->last,
				'inr' => $result->INR->last,
				'isk' => $result->ISK->last,
				'jpy' => $result->JPY->last,
				'krw' => $result->KRW->last,
				'nzd' => $result->NZD->last,
				'pln' => $result->PLN->last,
				'rub' => $result->RUB->last,
				'sek' => $result->SEK->last,
				'sgd' => $result->SGD->last,
				'thb' => $result->THB->last,
				'try' => $result->TRY->last,
				'twd' => $result->TWD->last
			];
		} catch (GuzzleException $e) {
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
}
