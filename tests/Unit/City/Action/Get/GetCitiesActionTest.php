<?php

declare(strict_types=1);

namespace App\Tests\Unit\City\Action\Get;

use App\City\Action\Get\GetCitiesAction;
use App\City\Action\Get\GetCitiesActionRequest;
use App\City\Repository\CityRepositoryInterface;
use App\Country\Exception\CountryNotFoundException;
use App\Country\Repository\CountryRepositoryInterface;
use App\State\Exception\StateNotFoundException;
use App\State\Repository\StateRepositoryInterface;
use App\Tests\Unit\City\CityDummy;
use App\Tests\Unit\Country\CountryDummy;
use App\Tests\Unit\State\StateDummy;
use PHPUnit\Framework\TestCase;

class GetCitiesActionTest extends TestCase
{
    private CityRepositoryInterface $cityRepository;
    private StateRepositoryInterface $stateRepository;
    private CountryRepositoryInterface $countryRepository;

    private string $stateCode = 'NJ';
    private string $countryIso2 = 'US';
    private string $title = 'California';
    private int $offset = 0;
    private int $limit = 10;

    private GetCitiesActionRequest $request;

    protected function setUp(): void
    {
        $this->cityRepository = $this->getMockBuilder(CityRepositoryInterface::class)->getMock();
        $this->stateRepository = $this->getMockBuilder(StateRepositoryInterface::class)->getMock();
        $this->countryRepository = $this->getMockBuilder(CountryRepositoryInterface::class)->getMock();

        $this->request = new GetCitiesActionRequest();
        $this->request->title = $this->title;
        $this->request->countryIso2 = $this->countryIso2;
        $this->request->stateCode = $this->stateCode;
        $this->request->limit = $this->limit;
        $this->request->offset = $this->offset;
    }

    public function testShouldThrowStateNotFoundExceptionIfNotFound(): void
    {
        $this->stateRepository
            ->expects($this->once())
            ->method('findByCode')
            ->with($this->stateCode)
            ->willReturn(null);

        $action = new GetCitiesAction($this->cityRepository, $this->stateRepository, $this->countryRepository);

        $this->expectException(StateNotFoundException::class);
        $this->expectExceptionMessage("State '$this->stateCode' not found.");

        $action->run($this->request);
    }

    public function testShouldThrowCountryNotFoundExceptionIfNotFound(): void
    {
        $this->stateRepository
            ->expects($this->once())
            ->method('findByCode')
            ->with($this->stateCode)
            ->willReturn(StateDummy::get());

        $this->countryRepository
            ->expects($this->once())
            ->method('findByIso2')
            ->with($this->countryIso2)
            ->willReturn(null);

        $action = new GetCitiesAction($this->cityRepository, $this->stateRepository, $this->countryRepository);

        $this->expectException(CountryNotFoundException::class);
        $this->expectExceptionMessage("Country '$this->countryIso2' not found.");

        $action->run($this->request);
    }

    public function testShouldReturnAValidGetCititesResponse(): void
    {
        $state = StateDummy::get();
        $this->stateRepository
            ->expects($this->once())
            ->method('findByCode')
            ->with($this->stateCode)
            ->willReturn($state);

        $country = CountryDummy::get();
        $this->countryRepository
            ->expects($this->once())
            ->method('findByIso2')
            ->with($this->countryIso2)
            ->willReturn($country);

        $city = CityDummy::get($country, $state);

        $this->cityRepository
            ->expects($this->once())
            ->method('list')
            ->with(
                $this->offset,
                $this->limit,
                $this->request->id,
                $this->request->title,
                $state,
                $country,
            )
            ->willReturn([$city]);

        $action = new GetCitiesAction($this->cityRepository, $this->stateRepository, $this->countryRepository);
        $actual = $action->run($this->request);

        $this->assertCount(1, $actual->cities);
        $this->assertEquals($city->getId(), $actual->cities[0]->id);
        $this->assertEquals($city->getTitle(), $actual->cities[0]->title);
    }

}