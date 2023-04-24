<?php

namespace App\Service;

use App\Entity;
use App\Exception\FruityViceApiException;
use App\Repository\FruitRepositoryInterface;

class FruitTablePopulatorService
{
    public function __construct(
        private readonly FruitRepositoryInterface $fruitRepository,
        private readonly FruityViceApiService $fruityViceApiService
    ) { }

    /**
     * @throws FruityViceApiException
     */
    public function populate(): void
    {
        $fruits = $this->fruityViceApiService->getAllFruits();

        foreach ($fruits as $item) {
            $fruit = $this->fruitRepository->findByName($item['name']);
            if ($fruit !== null) {
                continue; // Fruit already exists, skip adding it to the database
            }

            $nutritions = $item['nutritions'];
            $fruit = Entity\Fruit::create(
                $item['name'],
                $item['family'],
                $item['order'],
                $item['genus'],
                $nutritions['calories'],
                $nutritions['fat'],
                $nutritions['sugar'],
                $nutritions['carbohydrates'],
                $nutritions['protein']
            );

            $this->fruitRepository->save($fruit, true);
        }
    }
}