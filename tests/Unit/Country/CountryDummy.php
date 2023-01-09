<?php

declare(strict_types=1);

namespace App\Tests\Unit\Country;

use App\Country\Entity\Country;
use App\Tests\Unit\Currency\CurrencyDummy;
use App\Tests\Unit\SubRegion\SubRegionDummy;
use App\Tests\Unit\Timezone\TimezoneDummy;
use Ramsey\Uuid\Uuid;

class CountryDummy
{
    public const ID = '01fc782d-6107-4ac2-b744-a37787f07fab';
    public const TITLE = 'United States';
    public const NUMERIC_CODE = '400';
    public const ISO3 = 'USA';
    public const ISO2 = 'US';
    public const FLAG = '🇺🇸';
    public const TLD = '.com';
    public const LONGITUDE = -97;
    public const LATITUDE = 38;
    public const PHONE_CODE = '1';

    public static function get(): Country
    {
        $countryId = Uuid::fromString(self::ID);
        $country = new Country($countryId);
        $country
            ->addTimezone(TimezoneDummy::get())
            ->setTitle(self::TITLE)
            ->setNumericCode(self::NUMERIC_CODE)
            ->setIso3(self::ISO3)
            ->setIso2(self::ISO2)
            ->setFlag(self::FLAG)
            ->setSubRegion(SubRegionDummy::get())
            ->setCurrency(CurrencyDummy::get())
            ->setTld(self::TLD)
            ->setPhoneCode(self::PHONE_CODE)
            ->setLatitude(self::LATITUDE)
            ->setLongitude(self::LONGITUDE)
            ->setCreatedAt();

        return $country;
    }
}