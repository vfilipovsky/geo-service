<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TimezoneRepository;
use App\Trait\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TimezoneRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Timezone
{
    use TimestampTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private Uuid $id;

    #[ORM\Column(length: 150, unique: true)]
    private string $title;

    #[ORM\Column(length: 150, unique: true)]
    private string $code;

    #[ORM\Column(length: 50)]
    private string $utc;

    public function __construct(?Uuid $id = null)
    {
        if ($id) {
            $this->id = $id;
        }
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getUtc(): string
    {
        return $this->utc;
    }

    public function setUtc(string $utc): self
    {
        $this->utc = $utc;

        return $this;
    }

}
