<?php

namespace App\Courses\Application\Service;

use App\Courses\Domain\RebateServiceInterface;

class RebateService implements RebateServiceInterface
{
    public function getRebate($terminId): int
    {
        return 10;
    }
}