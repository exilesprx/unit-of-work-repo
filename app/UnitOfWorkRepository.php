<?php

namespace App;

use App\Contracts\Entity;
use App\Contracts\EntityRepository;
use App\Contracts\TransactionContract;
use App\Entities\TransactionContext;
use App\Entities\Units;
use Closure;
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

    private function getHandler(Entity $entity): Closure
    {
        return function(TransactionContext $transactionContext, Closure $next) use ($entity) {
            $repo = $this->getEntityRepo($entity);

            if ($repo->save($entity)) {
                $transactionContext->incrementSaves();

                return $next($transactionContext);
            }

            $transactionContext->incrementFailures();

            return $next($transactionContext);
        };
    }

    private function getEntityRepo(Entity $entity): EntityRepository
    {
        return $this->container->make($entity->getRepository());
    }
}