<?php

declare(strict_types=1);

namespace App\Factory;

interface BuilderInterface
{
    public static function builder(): self;
}