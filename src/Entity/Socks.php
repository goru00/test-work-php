<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\SocksRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\SocksBySlug;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Range;

#[ORM\Entity(repositoryClass: SocksRepository::class)]
#[
    ApiResource(
        collectionOperations: [
            "get" => [
                "method" => "GET",
                "path" => "/socks",
                "status" => 200
            ],
            "post_income" => [
                "method" => "POST",
                "path" => "/socks/income",
                "status" => 201,
            ],
            "post_outcome" => [
                "method" => "POST",
                "path" => "/socks/outcome",
                "status" => 201
            ]
        ],
        itemOperations: ["get"]
    )
]

class Socks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $color = null;

    #[ORM\Column]
    #[Range(
        notInRangeMessage: 'Задан интервал меньше {{ min }} или больше {{ max }}',
        min: 0,
        max: 100,
    )]
    private ?int $cottonPart = null;

    #[ORM\Column]
    #[GreaterThan(0)]
    private ?int $quantity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getCottonPart(): ?int
    {
        return $this->cottonPart;
    }

    public function setCottonPart(int $cottonPart): self
    {
        $this->cottonPart = $cottonPart;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
