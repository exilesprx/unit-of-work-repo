<?php

namespace App\Contracts;

interface TransactionContract
{
    public function begin() : void;

    public function commit() : void;

    public function rollback() : void;
}