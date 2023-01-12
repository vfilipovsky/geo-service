<?php

declare(strict_types=1);

namespace App\Currency\Controller\Response;

use App\Currency\Entity\Currency;
use Ramsey\Uuid\UuidInterface;

class CurrencyResponse
{
    public UuidInterface $id;
    public string $name;
    public string $code;
    public string $symbol;

    public function __construct(Currency $currency)
    {
        $this->id = $currency->getId();
        $this->name = $currency->getName();
        $this->code = $currency->getCode();
        $this->symbol = $currency->getSymbol();
    }
}