<?php

declare(strict_types=1);

namespace App\Action\SubRegion\Create;

use App\Controller\Response\SubRegionResponse;
use App\Entity\SubRegion;

readonly class CreateSubRegionActionResponse
{
    public SubRegionResponse $subRegionResponse;

    public function __construct(SubRegion $subRegion)
    {
        $this->subRegionResponse = new SubRegionResponse($subRegion);
    }
}