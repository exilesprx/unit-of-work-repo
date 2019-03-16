<?php

namespace Tests\Models;

use App\Contracts\Entity;
use Tests\Repositories\UserRepository;

class User implements Entity
{
    protected $saved;

    public function __construct()
    {
        $this->saved = false;
    }

    public function save() : void
    {
        $this->saved = true;
    }

    public function isSaved() : bool
    {
        return $this->saved;
    }

    public function getRepository(): string
    {
        return UserRepository::class;
    }
}