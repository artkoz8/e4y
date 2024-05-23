<?php

namespace App\Courses\Application\Service;

use App\Common\ValueObject\Money;
use App\Entity\Training;
use App\Courses\Domain\RebateServiceInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class CalculateCourseDiscountService
{
    public function __construct(
        #[Autowire(service: RebateServiceInterface::class)]
        private RebateServiceInterface $rebateService
    ) {}

    public function calculateDiscount(Training $course): Money
    {
        $rebatePercentage = $this->rebateService->getRebate($course->getId())/100;
        return $course->getPrice()->multiplication($rebatePercentage);
    }
}