<?php

namespace App\Services\ExtraCurrency\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed all()
 *
 * @see \App\Services\ExtraCurrency\Contracts\Factory
 */
class ExtraCurrency extends Facade
{
    protected static function getFacadeAccessor() { return 'extra_currency'; }
}
