<?php

namespace App\Contracts;

interface EntityRepository
{
    public function save(Entity $entity) : bool;
}