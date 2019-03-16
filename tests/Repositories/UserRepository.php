<?php

namespace Tests\Repositories;

use App\Contracts\Entity;
use App\Contracts\EntityRepository;
use Tests\Models\User;

class UserRepository implements EntityRepository
{
    private $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function save(Entity $user): bool
    {
        if(!$user instanceof User) {
             return false;
        }

        $user->save();

        return $user->isSaved();
    }
}