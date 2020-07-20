<?php


namespace Askync\Utils\Utils;


use Illuminate\Support\Collection;

class ResourceWrapper
{
    public $collection;
    public $resource;
    public function wrap($collection=null, $resource=null)
    {
        $this->collection = $collection;
        $this->resource = $resource;
        return $this;
    }
}
