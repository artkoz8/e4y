<?php

namespace App\Common\ValueObject;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use LogicException;

#[Embeddable]
final readonly class Money
{
    public const PLN = 'PLN';
    public const AVAILABLE_CURRENCY = [self::PLN];

    final public function __construct(
        #[Column(type: 'integer', nullable: false)]
        private int    $amount,
        #[Column(type: 'string', nullable: false)]
        private string $currency
    )
    {
        if ($amount < 0) {
            throw new LogicException("Value must be greater than 0");
        }

        if (!in_array($currency, self::AVAILABLE_CURRENCY)) {
            throw new LogicException("Invalid currency");
        }
    }

    public static function PLNFromFloat(float $amount): self
    {
        return new self(round($amount * 100, 0), self::PLN);
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getAmountFloat(): float
    {
        return $this->amount/100;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function multiplication(float $multiplier): self
    {
        return new self(round($this->amount * $multiplier, 0), self::PLN);
    }

    public function subtraction(Money $subtraction): self
    {
        return new self($this->amount - $subtraction->amount, self::PLN);
    }
}