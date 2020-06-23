<?php

namespace Samirdjelal\Cryptocurrency;

use Illuminate\Support\ServiceProvider;

class CryptocurrencyServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 */
	public function boot()
	{
		/*
		 * Optional methods to load your package assets
		 */
		// $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'cryptocurrency');
		// $this->loadViewsFrom(__DIR__.'/../resources/views', 'cryptocurrency');
		$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
		$this->loadRoutesFrom(__DIR__ . '/routes.php');

		if ($this->app->runningInConsole()) {
			$this->publishes([
				__DIR__ . '/../config/config.php' => config_path('cryptocurrency.php'),
			], 'config');

			if (!class_exists('CreateCryptocurrencyTable')) {
				$this->publishes([
					__DIR__ . '/../database/migrations/create_cryptocurrency_table.php.stub' => database_path('/migrations/' . date('Y_m_d_His', time()) . '_create_cryptocurrency_table.php')
				], 'migrations');
			}


			// Publishing the views.
			/*$this->publishes([
				 __DIR__.'/../resources/views' => resource_path('views/vendor/cryptocurrency'),
			], 'views');*/

			// Publishing assets.
			/*$this->publishes([
				 __DIR__.'/../resources/assets' => public_path('vendor/cryptocurrency'),
			], 'assets');*/

			// Publishing the translation files.
			/*$this->publishes([
				 __DIR__.'/../resources/lang' => resource_path('lang/vendor/cryptocurrency'),
			], 'lang');*/


			// Registering package commands.
			// $this->commands([]);
		}
	}

	/**
	 * Register the application services.
	 */
	public function register()
	{
		// Automatically apply the package configuration
		$this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'cryptocurrency');

		// Register the main class to use with the facade
		$this->app->singleton('cryptocurrency', function () {
			return new Cryptocurrency();
		});
	}
}
