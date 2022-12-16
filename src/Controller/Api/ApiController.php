<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiController extends AbstractController
{
    public const FORMAT_JSON = 'json';

    public function __construct(
        private readonly ValidatorInterface  $validator,
        private readonly SerializerInterface $serializer,
    )
    {
    }

    /**
     * @throws ValidationException
     */
    public function handleRequest(Request $request, string $class, string $format = self::FORMAT_JSON)
    {
        $req = $this->serializer->deserialize($request->getContent(), $class, $format);
        $errors = $this->validator->validate($req);

        if (count($errors) > 0) {
            throw new ValidationException((string)$errors);
        }

        return $req;
    }
}