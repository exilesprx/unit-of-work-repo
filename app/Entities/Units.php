<?php

namespace App\Entities;

use Closure;

class Units
{
    private $units;

    public function __construct()
    {
        $this->units = [];
    }

    public function add(Closure $closure) : void
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