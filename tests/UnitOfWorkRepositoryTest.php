<?php

namespace Tests;

use Tests\Entities\Database;
use App\Entities\Units;
use Tests\Models\FailedUser;
use Tests\Models\User;
use App\UnitOfWorkRepository;
use Illuminate\Container\Container;
use Illuminate\Pipeline\Pipeline;
use PHPUnit\Framework\TestCase;

class UnitOfWorkRepositoryTest extends TestCase
{
    public function testCommittingSingleEntity()
    {
        $units = new Units();
        $pipeline = new Pipeline();
        $container = new Container();
        $database = new Database();

        $repo = new UnitOfWorkRepository($units, $pipeline, $container, $database);

        $user = new User();

        $repo->add($user);

        $repo->save();

        $this->assertTrue($user->isSaved());
        $this->assertTrue($database->transactionBegan());
        $this->assertTrue($database->transactionCommitted());
        $this->assertTrue($units->isEmpty());
    }

    public function testCommittingMultipleEntities()
    {
        $units = new Units();
        $pipeline = new Pipeline();
        $container = new Container();
        $database = new Database();

        $repo = new UnitOfWorkRepository($units, $pipeline, $container, $database);

        $first = new User();
        $second = new User();
        $third = new User();
        $fourth = new User();

        $repo->add($first);
        $repo->add($second);
        $repo->add($third);
        $repo->add($fourth);

        $repo->save();

        $this->assertTrue($first->isSaved());
        $this->assertTrue($second->isSaved());
        $this->assertTrue($third->isSaved());
        $this->assertTrue($fourth->isSaved());
        $this->assertTrue($database->transactionBegan());
        $this->assertTrue($database->transactionCommitted());
        $this->assertTrue($units->isEmpty());
    }

    public function testTransactionRolledBack()
    {
        $units = new Units();
        $pipeline = new Pipeline();
        $container = new Container();
        $database = new Database();

        $repo = new UnitOfWorkRepository($units, $pipeline, $container, $database);

        $user = new FailedUser();

        $repo->add($user);

        $repo->save();

        $this->assertFalse($user->isSaved());
        $this->assertTrue($database->transactionBegan());
        $this->assertTrue($database->transactionRolledBack());
        $this->assertTrue($units->isEmpty());
    }
}