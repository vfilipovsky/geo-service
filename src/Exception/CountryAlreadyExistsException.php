<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class CountryAlreadyExistsException extends ApplicationException
{
    public function __construct()
    {
        parent::__construct('Country already exists.', code: Response::HTTP_CONFLICT);
    }
}