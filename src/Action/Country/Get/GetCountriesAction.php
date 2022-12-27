<?php

declare(strict_types=1);

namespace App\Action\Country\Get;

use App\Exception\CurrencyNotFoundException;
use App\Exception\SubRegionNotFoundException;
use App\Repository\CountryRepositoryInterface;
use App\Repository\CurrencyRepositoryInterface;
use App\Repository\SubRegionRepositoryInterface;

readonly class GetCountriesAction implements GetCountriesActionInterface
{
    public function __construct(
        private CountryRepositoryInterface   $countryRepository,
        private CurrencyRepositoryInterface  $currencyRepository,
        private SubRegionRepositoryInterface $subRegionRepository,
    )
    {
    }

    /**
     * @throws CurrencyNotFoundException
     * @throws SubRegionNotFoundException
     */
    public function run(GetCountriesActionRequest $request): GetCountriesActionResponse
    {
        $currency = null;
        if ($request->currencyCode) {
            $currency = $this->currencyRepository->findByCode($request->currencyCode);

            if (!$currency) {
                throw new CurrencyNotFoundException($request->currencyCode);
            }
        }

        $subRegion = null;
        if ($request->subRegion) {
            $subRegion = $this->subRegionRepository->findByTitle($request->subRegion);

            if (!$subRegion) {
                throw new SubRegionNotFoundException($request->subRegion);
            }
        }

        return new GetCountriesActionResponse(
            $this->countryRepository->list(
                $request->offset,
                $request->limit,
                $request->id,
                $request->title,
                $request->nativeTitle,
                $request->iso2,
                $request->iso3,
                $request->phoneCode,
                $request->numericCode,
                $request->tld,
                $currency,
                $subRegion,
            )
        );
    }

}