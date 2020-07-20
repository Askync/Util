<?php
namespace Askync\Utils\Facades;

use Illuminate\Support\Facades\Facade;

class Teleport extends Facade {

    protected static function getFacadeAccessor() { return \Askync\Utils\Utils\Teleport::class; }
}
