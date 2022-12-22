<?php

declare(strict_types=1);

namespace App\Tests\Unit\Action\Region\Create;

use App\Action\Region\Create\CreateRegionAction;
use App\Action\Region\Create\CreateRegionActionRequest;
use App\Action\Region\Create\CreateRegionActionResponse;
use App\Entity\Region;
use App\Exception\RegionAlreadyExistsException;
use App\Factory\RegionFactoryInterface;
use App\Repository\RegionRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CreateRegionActionTest extends TestCase
{
    private readonly RegionFactoryInterface $factory;
    private readonly RegionRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->factory = $this->getMockBuilder(RegionFactoryInterface::class)->getMock();
        $this->repository = $this->getMockBuilder(RegionRepositoryInterface::class)->getMock();
    }

    public function testShouldReturnAValidResponseOnSuccess(): void
    {
        $id = Uuid::uuid7();
        $title = 'Europe';

        $request = new CreateRegionActionRequest($title);
        $this->assertEquals($title, $request->title);

        $region = new Region($id);
        $region->setTitle($title);
        $region->setCreatedAt();

        $expectedResponse = new CreateRegionActionResponse($region);

        $this->repository->expects($this->once())->method('findByTitle')->with($title)->willReturn(null);
        $this->factory->expects($this->once())->method('setTitle')->with($title)->willReturn($this->factory);
        $this->factory->expects($this->once())->method('create')->willReturn($region);

        $action = new CreateRegionAction($this->repository, $this->factory);

        try {
            $actual = $action->run($request);
        } catch (RegionAlreadyExistsException $e) {
            $this->fail('Must not thrown an exception: ' . $e->getMessage());
        }

        $this->assertEquals($expectedResponse->regionResponse->id, $actual->regionResponse->id);
        $this->assertEquals($expectedResponse->regionResponse->title, $actual->regionResponse->title);
        $this->assertEquals($expectedResponse->regionResponse->createdAt, $actual->regionResponse->createdAt);
        $this->assertEquals($expectedResponse->regionResponse->updatedAt, $actual->regionResponse->updatedAt);
    }

    public function testShouldThrowAnErrorIfTitleHasBeenAlreadyTaken(): void
    {
        $title = 'Europe';
        $this->repository
            ->expects($this->once())
            ->method('findByTitle')
            ->with($title)
            ->willReturn(new Region());

        $action = new CreateRegionAction($this->repository, $this->factory);
        $request = new CreateRegionActionRequest($title);

        $this->expectException(RegionAlreadyExistsException::class);
        $action->run($request);
    }

}