<?php

namespace App\Entities;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class TransactionContext
{
    private $id;

    private $saves;

    private $failures;

    protected function __construct(UuidInterface $uuid, int $saves, int $failures)
    {
        $this->id = $uuid;

        $this->saves = $saves;

        $this->failures = $failures;
    }

    public static function create() : self
    {
        return new self(
            Uuid::uuid4(),
            0,
            0
        );
    }

    public function incrementSaves() : void
    {
        $this->saves++;
    }

    public function incrementFailures() : void
    {
        $this->failures++;
    }

    public function hasFailures() : bool
    {
        return $this->failures > 0;
    }
}