<?php

namespace Olssonm\Roaring\Facades;

/**
 * Facade for the Roraing API when using with PHP
 */
class Roaring extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'roaring';
    }
}
