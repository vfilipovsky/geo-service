<?php

declare(strict_types=1);

namespace App\Action\SubRegion\Update;

use App\Exception\RegionNotFoundException;
use App\Exception\SubRegionNotFoundException;
use App\Repository\RegionRepositoryInterface;
use App\Repository\SubRegionRepositoryInterface;

readonly class UpdateSubRegionAction implements UpdateSubRegionActionInterface
{
    public function __construct(
        private SubRegionRepositoryInterface $subRegionRepository,
        private RegionRepositoryInterface $regionRepository,
    )
    {
    }

    /**
     * @throws RegionNotFoundException
     * @throws SubRegionNotFoundException
     */
    public function run(UpdateSubRegionActionRequest $request): UpdateSubRegionActionResponse
    {
        $subRegion = $this->subRegionRepository->findById($request->id);

        if (!$subRegion) {
            throw new SubRegionNotFoundException();
        }

        if ($request->regionId) {
            $region = $this->regionRepository->findById($request->regionId);

            if (!$region) {
                throw new RegionNotFoundException();
            }

            $subRegion->setRegion($region);
        }

        $subRegion->setTitle($request->title);
        $this->subRegionRepository->save($subRegion, true);

        return new UpdateSubRegionActionResponse($subRegion);
    }

}