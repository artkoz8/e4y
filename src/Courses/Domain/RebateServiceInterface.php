<?php

namespace App\Courses\Domain;

interface RebateServiceInterface
{
    public function getRebate($terminId): int;
}