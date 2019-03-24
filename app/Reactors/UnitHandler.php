<?php


namespace App\Reactors;


use App\Contracts\Entity;
use App\Contracts\EntityRepository;
use App\Entities\TransactionContext;
use Closure;

class UnitHandler
{
    private $entity;

    private $repository;

    public function __construct(Entity $entity, EntityRepository $repository)
    {
        $this->entity = $entity;

        $this->repository = $repository;
    }

    public static function saves(Entity $entity, EntityRepository $repository): self
    {
        return new self($entity, $repository);
    }

    public function handle(TransactionContext $transactionContext, Closure $next)
    {
        if ($this->repository->save($this->entity)) {
            $transactionContext->incrementSaves();

            return $next($transactionContext);
        }

        $transactionContext->incrementFailures();

        return $next($transactionContext);
    }
}