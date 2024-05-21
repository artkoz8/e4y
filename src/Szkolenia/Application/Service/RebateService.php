<?php

namespace App\Szkolenia\Application\Service;

use App\Szkolenia\Domain\RebateServiceInterface;

class RebateService implements RebateServiceInterface
{
    public function getRebate($terminId): int
    {
        return 10;
    }
}