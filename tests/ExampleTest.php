<?php

namespace Samirdjelal\Cryptocurrency\Tests;

use Orchestra\Testbench\TestCase;
use Samirdjelal\Cryptocurrency\CryptocurrencyServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [CryptocurrencyServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
