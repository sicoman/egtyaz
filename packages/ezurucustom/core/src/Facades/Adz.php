<?php

namespace EzuruCustom\Core\Facades;

use Illuminate\Support\Facades\Facade;

class EzuruCustom extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'EzuruCustom';
    }
}
