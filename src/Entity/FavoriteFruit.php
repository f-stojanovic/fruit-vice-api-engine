<?php

namespace App\Entity;

use App\Repository\FavoriteFruitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriteFruitRepository::class)]
class FavoriteFruit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Fruit::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Fruit $fruit;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getFruit(): ?Fruit
    {
        return $this->fruit;
    }

    public function setFruit(?Fruit $fruit): self
    {
        $this->fruit = $fruit;

        return $this;
    }
}
