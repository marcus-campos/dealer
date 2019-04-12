<?php

namespace MarcusCampos\Dealer;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MarcusCampos\Dealer\Skeleton\SkeletonClass
 */
class DealerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'dealer';
    }
}
