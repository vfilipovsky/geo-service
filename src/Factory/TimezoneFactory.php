<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Timezone;

class TimezoneFactory implements TimezoneFactoryInterface
{
    private Timezone $timezone;

    public function __construct()
    {
        $this->timezone = new Timezone();
    }

    public function setTitle(string $title): TimezoneFactoryInterface
    {
        $this->timezone->setTitle($title);
        return $this;
    }

    public function setCode(string $code): TimezoneFactoryInterface
    {
        $this->timezone->setCode($code);
        return $this;
    }

    public function setUtc(string $utc): TimezoneFactoryInterface
    {
        $this->timezone->setUtc($utc);
        return $this;
    }

    public function create(): Timezone
    {
        return $this->timezone;
    }

}