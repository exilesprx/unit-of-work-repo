<?php

namespace App\Contracts;

interface Entity
{
    public function getRepository() : string;
}