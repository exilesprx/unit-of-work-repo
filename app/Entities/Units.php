<?php

namespace App\Entities;

use App\Reactors\UnitHandler;

class Units
{
    private $units;

    public function __construct()
    {
        $this->units = [];
    }

    public function add(UnitHandler $closure) : void
    {
        array_push($this->units, $closure);
    }

    public function reset() : void
    {
        $this->units = [];
    }

    public function toArray() : array
    {
        return $this->units;
    }

    public function isEmpty() : bool
    {
        return count($this->units) == 0;
    }
}