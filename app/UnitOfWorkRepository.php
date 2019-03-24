<?php

namespace App;

use App\Contracts\Entity;
use App\Contracts\EntityRepository;
use App\Contracts\TransactionContract;
use App\Entities\TransactionContext;
use App\Reactors\UnitHandler;
use App\Entities\Units;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Pipeline\Pipeline;

class UnitOfWorkRepository
{
    private $units;

    private $pipeline;

    private $container;

    private $transaction;

    public function __construct(Units $units, Pipeline $pipeline, Container $container, TransactionContract $transaction)
    {
        $this->pipeline = $pipeline;

        $this->container = $container;

        $this->units = $units;

        $this->transaction = $transaction;
    }

    public function add(Entity $entity): void
    {
        $this->units->add($this->getHandler($entity));
    }

    public function save(): void
    {
        $this->transaction->begin();

        $this->pipeline->send(TransactionContext::create())
            ->through($this->units->toArray())
            ->then(function (TransactionContext $transactionContext) {

                $this->units->reset();

                if ($transactionContext->hasFailures()) {
                    $this->transaction->rollback();
                    return;
                }

                $this->transaction->commit();
                return;
            });
    }

    private function getHandler(Entity $entity): UnitHandler
    {
        $repo = $this->getEntityRepo($entity);

        return UnitHandler::saves($entity, $repo);
    }

    private function getEntityRepo(Entity $entity): EntityRepository
    {
        return $this->container->make($entity->getRepository());
    }
}