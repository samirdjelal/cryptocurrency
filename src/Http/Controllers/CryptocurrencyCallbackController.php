<?php


namespace Samirdjelal\Cryptocurrency\Http\Controllers;

use App\Http\Controllers\Controller;

class CryptocurrencyCallbackController extends Controller
{
	public function __invoke($orderId, $secretKey)
	{
		if ($secretKey != config('cryptocurrency.callback_secret_key')) {
			return 'wrong secret key';
		}

		// todo: place an order! $orderId
		return 'placing an order... '.$orderId;
	}
}
