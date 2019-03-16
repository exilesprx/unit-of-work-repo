<?php

namespace Tests\Entities;

use App\Contracts\TransactionContract;

class Database implements TransactionContract
{
    private $begin;

    private $commit;

    private $rollback;

    public function __construct()
    {
        $this->begin = false;

        $this->commit = false;

        $this->rollback = false;
    }

    public function begin() : void
    {
        $this->begin = true;
    }

    public function commit() : void
    {
        $this->commit = true;
    }

    public function rollback() : void
    {
        $this->rollback = true;
    }

    public function transactionBegan() : bool
    {
        return $this->begin;
    }

    public function transactionCommitted() : bool
    {
        return $this->commit;
    }

    public function transactionRolledBack() : bool
    {
        return $this->rollback;
    }
}