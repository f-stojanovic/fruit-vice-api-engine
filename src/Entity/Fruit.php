<?php

namespace App\Entity;

use App\Repository\FruitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FruitRepository::class)]
class Fruit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    #[ORM\Column(type: "string", length: 255)]
    private string $family;

    #[ORM\Column(type: "string", length: 255)]
    private string $fruitOrder;

    #[ORM\Column(type: "string", length: 255)]
    private string $genus;

    #[ORM\Column(type: "integer", options: ["default" => 0])]
    private int $calories;

    #[ORM\Column(type: "float")]
    private float $fat;

    #[ORM\Column(type: "float")]
    private float $sugar;

    #[ORM\Column(type: "float")]
    private float $carbohydrates;

    #[ORM\Column(type: "float")]
    private float $protein;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getFamily(): string
    {
        return $this->family;
    }

    public function setFamily(string $family): void
    {
        $this->family = $family;
    }

    public function getOrder(): string
    {
        return $this->fruitOrder;
    }

    public function setOrder(string $order): void
    {
        $this->fruitOrder = $order;
    }

    public function getGenus(): string
    {
        return $this->genus;
    }

    public function setGenus(string $genus): void
    {
        $this->genus = $genus;
    }

    public function getCalories(): int
    {
        return $this->calories;
    }

    public function setCalories(int $calories): void
    {
        $this->calories = $calories;
    }

    public function getFat(): float
    {
        return $this->fat;
    }

    public function setFat(float $fat): void
    {
        $this->fat = $fat;
    }

    public function getSugar(): float
    {
        return $this->sugar;
    }

    public function setSugar(float $sugar): void
    {
        $this->sugar = $sugar;
    }

    public function getCarbohydrates(): float
    {
        return $this->carbohydrates;
    }

    public function setCarbohydrates(float $carbohydrates): void
    {
        $this->carbohydrates = $carbohydrates;
    }

    public function getProtein(): float
    {
        return $this->protein;
    }

    public function setProtein(float $protein): void
    {
        $this->protein = $protein;
    }
}