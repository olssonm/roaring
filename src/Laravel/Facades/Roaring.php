<?php

namespace Olssonm\Roaring\Laravel\Facades;

/**
 * Facade for the Roraing API when using with PHP
 */
class RoaringFacade extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Roaring';
    }
}
