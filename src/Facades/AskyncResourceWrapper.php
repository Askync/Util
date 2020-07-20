<?php
namespace Askync\Utils\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Log;

/**
 * @method static void wrap(array $collection, string $resource)
 *
 * @see \Askync\Utils\Utils\Response
 */

class AskyncResourceWrapper extends Facade {


    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return AskyncResourceWrapper::class; }
}
