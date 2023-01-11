<?php

declare(strict_types=1);

namespace App\Tests\Unit\Airport\Action\Get;

use App\Airport\Action\Get\GetAirportsAction;
use App\Airport\Action\Get\GetAirportsActionRequest;
use App\Airport\Repository\AirportRepositoryInterface;
use App\City\Entity\City;
use App\City\Exception\CityNotFoundException;
use App\City\Repository\CityRepositoryInterface;
use App\Tests\Unit\Airport\AirportDummy;
use App\Tests\Unit\City\CityDummy;
use App\Tests\Unit\Timezone\TimezoneDummy;
use App\Timezone\Exception\TimezoneNotFoundException;
use App\Timezone\Repository\TimezoneRepositoryInterface;
use PHPUnit\Framework\TestCase;

class GetAirportsActionTest extends TestCase
{
    private AirportRepositoryInterface $airportRepository;
    private CityRepositoryInterface $cityRepository;
    private TimezoneRepositoryInterface $timezoneRepository;

    private GetAirportsActionRequest $request;
    private string $cityTitle = 'Trondheim';
    private string $iata = 'TRD';
    private string $icao = 'ENVA';
    private string $title = 'Trondheim Airport';
    private string $timezone = 'Europe/Oslo';

    protected function setUp(): void
    {
        $this->airportRepository = $this->getMockBuilder(AirportRepositoryInterface::class)->getMock();
        $this->cityRepository = $this->getMockBuilder(CityRepositoryInterface::class)->getMock();
        $this->timezoneRepository = $this->getMockBuilder(TimezoneRepositoryInterface::class)->getMock();

        $this->request = new GetAirportsActionRequest();
        $this->request->cityTitle = $this->cityTitle;
        $this->request->title = $this->title;
        $this->request->iata = $this->iata;
        $this->request->icao = $this->icao;
        $this->request->timezone = $this->timezone;
    }

    public function testShouldThrowCityNotFoundExceptionIfNotFound(): void
    {
        $this->cityRepository
            ->expects($this->once())
            ->method('findByTitle')
            ->with($this->cityTitle)
            ->willReturn(null);

        $action = new GetAirportsAction($this->airportRepository, $this->cityRepository, $this->timezoneRepository);

        $this->expectException(CityNotFoundException::class);
        $this->expectExceptionMessage("City '$this->cityTitle' not found.");

        $action->run($this->request);
    }

    public function testShouldThrowTimezoneNotFoundExceptionIfNotFound(): void
    {
        $this->cityRepository
            ->expects($this->once())
            ->method('findByTitle')
            ->with($this->cityTitle)
            ->willReturn(new City());

        $this->timezoneRepository
            ->expects($this->once())
            ->method('findByCode')
            ->with($this->timezone)
            ->willReturn(null);

        $action = new GetAirportsAction($this->airportRepository, $this->cityRepository, $this->timezoneRepository);

        $this->expectException(TimezoneNotFoundException::class);
        $this->expectExceptionMessage("Timezone '$this->timezone' not found.");

        $action->run($this->request);
    }

    public function testShouldReturnAValidResponse(): void
    {
        $city = CityDummy::get();
        $this->cityRepository
            ->expects($this->once())
            ->method('findByTitle')
            ->with($this->cityTitle)
            ->willReturn($city);

        $timezone = TimezoneDummy::get();
        $this->timezoneRepository
            ->expects($this->once())
            ->method('findByCode')
            ->with($this->timezone)
            ->willReturn($timezone);

        $airport = AirportDummy::get();
        $airports[] = $airport;
        $this->airportRepository
            ->expects($this->once())
            ->method('list')
            ->with(
                $this->request->offset,
                $this->request->limit,
                $this->request->id,
                $this->request->title,
                $this->request->iata,
                $this->request->icao,
                $timezone,
                $city
            )
            ->willReturn($airports);

        $action = new GetAirportsAction($this->airportRepository, $this->cityRepository, $this->timezoneRepository);
        $actual = $action->run($this->request);

        $this->assertCount(1, $actual->airports);
        $this->assertEquals($airport->getId(), $actual->airports[0]->id);
    }
}