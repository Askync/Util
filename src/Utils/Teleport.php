<?php

namespace Askync\Utils\Utils;

class Teleport
{

    protected $bag = [];

    /**
     * @param \stdClass $bag
     */
    public function set($key, $data): void
    {
        $this->bag[$key] = $data;
    }
    public function get($key)
    {
        if(isset($this->bag[$key])) {
            return $this->bag[$key];
        }
        return null;
    }

}
