<?php

namespace Samirdjelal\Cryptocurrency;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Samirdjelal\Cryptocurrency\Skeleton\SkeletonClass
 */
class CryptocurrencyFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cryptocurrency';
    }
}
