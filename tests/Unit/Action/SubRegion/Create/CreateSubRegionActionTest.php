<?php

declare(strict_types=1);

namespace App\Tests\Unit\Action\SubRegion\Create;

use App\Action\SubRegion\Create\CreateSubRegionAction;
use App\Action\SubRegion\Create\CreateSubRegionActionRequest;
use App\Action\SubRegion\Create\CreateSubRegionActionResponse;
use App\Entity\SubRegion;
use App\Exception\SubRegionAlreadyExistsException;
use App\Factory\SubRegionFactoryInterface;
use App\Region\Entity\Region;
use App\Region\Exception\RegionNotFoundException;
use App\Region\Repository\RegionRepositoryInterface;
use App\Repository\SubRegionRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CreateSubRegionActionTest extends TestCase
{
    private readonly SubRegionFactoryInterface $factory;
    private readonly SubRegionRepositoryInterface $subRegionRepository;
    private readonly RegionRepositoryInterface $regionRepository;

    protected function setUp(): void
    {
        $this->factory = $this->getMockBuilder(SubRegionFactoryInterface::class)->getMock();
        $this->regionRepository = $this->getMockBuilder(RegionRepositoryInterface::class)->getMock();
        $this->subRegionRepository = $this->getMockBuilder(SubRegionRepositoryInterface::class)->getMock();
    }

    public function testShouldReturnAValidResponseOnSuccess(): void
    {
        $regionId = Uuid::uuid4();
        $regionTitle = 'Europe';

        $region = new Region($regionId);
        $region->setTitle($regionTitle);
        $region->setCreatedAt();

        $subRegionId = Uuid::uuid4();
        $subRegionTitle = 'Eastern Europe';
        $subRegion = new SubRegion($subRegionId);
        $subRegion->setRegion($region);
        $subRegion->setTitle($subRegionTitle);
        $subRegion->setCreatedAt();

        $request = new CreateSubRegionActionRequest($subRegionTitle, $regionId->toString());
        $this->assertEquals($subRegionTitle, $request->title);

        $expectedResponse = new CreateSubRegionActionResponse($subRegion);

        $this->subRegionRepository
            ->expects($this->once())
            ->method('findByTitle')
            ->with($subRegionTitle)
            ->willReturn(null);

        $this->regionRepository
            ->expects($this->once())
            ->method('findById')
            ->with($regionId)
            ->willReturn($region);

        $this->factory->expects($this->once())->method('setTitle')->with($subRegionTitle)->willReturn($this->factory);
        $this->factory->expects($this->once())->method('setRegion')->with($region)->willReturn($this->factory);
        $this->factory->expects($this->once())->method('create')->willReturn($subRegion);
        $this->subRegionRepository->expects($this->once())->method('save')->with($subRegion, true);

        $action = new CreateSubRegionAction($this->regionRepository, $this->subRegionRepository, $this->factory);

        try {
            $actual = $action->run($request);
        } catch (RegionNotFoundException|SubRegionAlreadyExistsException $e) {
            $this->fail('Must not thrown an exception: ' . $e->getMessage());
        }

        $this->assertEquals($expectedResponse->subRegionResponse->id, $actual->subRegionResponse->id);
        $this->assertEquals($expectedResponse->subRegionResponse->title, $actual->subRegionResponse->title);
        $this->assertEquals($expectedResponse->subRegionResponse->createdAt, $actual->subRegionResponse->createdAt);
        $this->assertEquals($expectedResponse->subRegionResponse->updatedAt, $actual->subRegionResponse->updatedAt);
        $this->assertEquals($expectedResponse->subRegionResponse->region->id, $actual->subRegionResponse->region->id);
        $this->assertEquals($expectedResponse->subRegionResponse->region->title, $actual->subRegionResponse->region->title);
        $this->assertEquals($expectedResponse->subRegionResponse->region->createdAt, $actual->subRegionResponse->region->createdAt);
        $this->assertEquals($expectedResponse->subRegionResponse->region->updatedAt, $actual->subRegionResponse->region->updatedAt);
    }

    public function testShouldThrowAnErrorIfTitleHasBeenAlreadyTaken(): void
    {
        $title = 'Eastern Europe';
        $this->subRegionRepository
            ->expects($this->once())
            ->method('findByTitle')
            ->with($title)
            ->willReturn(new SubRegion());

        $action = new CreateSubRegionAction($this->regionRepository, $this->subRegionRepository, $this->factory);
        $request = new CreateSubRegionActionRequest($title, Uuid::uuid4()->toString());

        $this->expectException(SubRegionAlreadyExistsException::class);
        $action->run($request);
    }

}