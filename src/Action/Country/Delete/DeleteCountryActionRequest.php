<?php

declare(strict_types=1);

namespace App\Action\Country\Delete;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class DeleteCountryActionRequest
{
    public UuidInterface $id;

    public function __construct(string $id)
    {
        $this->id = Uuid::fromString($id);
    }
}