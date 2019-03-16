<?php

namespace Tests\Models;

class FailedUser extends User
{
    public function save(): void
    {
        $this->saved = false;
    }
}