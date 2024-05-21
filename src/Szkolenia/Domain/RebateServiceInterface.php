<?php

namespace App\Szkolenia\Domain;

interface RebateServiceInterface
{
    public function getRebate($terminId): int;
}