<?php
use Samirdjelal\Cryptocurrency\Http\Controllers\CryptocurrencyCallbackController;

Route::get('/cryptocurrency/callback/{orderId}/{secretKey}', CryptocurrencyCallbackController::class);

