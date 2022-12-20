<?php

declare(strict_types=1);

namespace App\Tests\Unit\Action\Currency\Get;

use App\Action\Currency\Get\GetCurrenciesActionRequest;
use App\Controller\Request\LimitOffsetParser;
use PHPUnit\Framework\TestCase;

class GetCurrenciesActionRequestTest extends TestCase
{
    public function testFromArrayShouldReturnNewInstanceOfRequest(): void
    {
        $limit = 10;
        $offset = 0;
        $name = 'Euro';
        $code = 'EUR';
        $symbol = '€';

        $request = [
            'limit' => $limit,
            'offset' => $offset,
            'name' => $name,
            'code' => $code,
            'symbol' => $symbol,
        ];

        $actual = GetCurrenciesActionRequest::fromArray($request);

        $this->assertEquals($limit, $actual->limit);
        $this->assertEquals($offset, $actual->offset);
        $this->assertEquals($name, $actual->name);
        $this->assertEquals($code, $actual->code);
        $this->assertEquals($symbol, $actual->symbol);
    }

    public function testShouldSetDefaultValuesOnNonNumericRequest(): void
    {
        $request = [
            'offset' => 'o',
            'limit' => '1o'
        ];

        $actual = GetCurrenciesActionRequest::fromArray($request);

        $this->assertEquals(LimitOffsetParser::DEFAULT_LIMIT, $actual->limit);
        $this->assertEquals(LimitOffsetParser::DEFAULT_OFFSET, $actual->offset);
    }
}