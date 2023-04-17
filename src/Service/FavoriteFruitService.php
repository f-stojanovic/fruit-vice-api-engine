<?php

namespace App\Service;

use App\Entity;
use App\Repository\FavoriteFruitRepository;
use App\Repository\FruitRepository;

class FavoriteFruitService
{
    /**
     * @var FavoriteFruitRepository
     */
    private FavoriteFruitRepository $favoriteFruitRepository;

    /**
     * @var FruitRepository
     */
    private FruitRepository $fruitRepository;

    public function __construct(
        FavoriteFruitRepository $favoriteFruitRepository,
        FruitRepository $fruitRepository
    ) {
        $this->favoriteFruitRepository = $favoriteFruitRepository;
        $this->fruitRepository         = $fruitRepository;
    }

    /**
     * @param $favoriteFruits
     * @return void
     */
    public function saveFavouriteFruits($favoriteFruits): void
    {
        foreach ($favoriteFruits as $item) {
            $favouriteFruit = new Entity\FavoriteFruit();
            $favouriteFruit->setFruit($item);

            $this->favoriteFruitRepository->save($favouriteFruit, true);
        }
    }

    /**
     * @return void
     */
    public function clearFavouriteFruits(): void
    {
        $favouriteFruits = $this->favoriteFruitRepository->findAll();

        if (count($favouriteFruits) > 0) {
            foreach ($favouriteFruits as $item) {
                $this->favoriteFruitRepository->remove($item, true);
            }
        }
    }

    /**
     * @return array|float|int|mixed|string
     */
    public function getAllFavouriteFruits(): mixed
    {
        $allFavouriteFruits = $this->favoriteFruitRepository->findAll();

        $favoriteFruitIds = array();
        foreach ($allFavouriteFruits as $favoriteFruit) {
            $favoriteFruitIds[] = $favoriteFruit->getFruit()->getId();
        }

        if (!empty($favoriteFruitIds)) {
            $queryBuilder = $this->fruitRepository->createQueryBuilder('f');
            $queryBuilder->where($queryBuilder->expr()->in('f.id', $favoriteFruitIds));
            $fruits = $queryBuilder->getQuery()->getResult();
        } else {
            $fruits = array();
        }

        return $fruits;
    }

    /**
     * @return array|int[]
     */
    public function getSumOfNutritionFacts(): array
    {
        $allFavouriteFruits = $this->favoriteFruitRepository->findAll();

        $favoriteFruitIds = array();
        foreach ($allFavouriteFruits as $favoriteFruit) {
            $favoriteFruitIds[] = $favoriteFruit->getFruit()->getId();
        }

        if (count($favoriteFruitIds) > 0) {
            $queryBuilder = $this->fruitRepository->createQueryBuilder('f');
            $queryBuilder->where($queryBuilder->expr()->in('f.id', $favoriteFruitIds));
            $fruits = $queryBuilder->getQuery()->getResult();

            // calculate the sum of nutrition facts
            $sumCalories = 0;
            $sumFat = 0;
            $sumSugar = 0;
            $sumCarbohydrates = 0;
            $sumProtein = 0;

            foreach ($fruits as $fruit) {
                $sumCalories += $fruit->getCalories();
                $sumFat += $fruit->getFat();
                $sumSugar += $fruit->getSugar();
                $sumCarbohydrates += $fruit->getCarbohydrates();
                $sumProtein += $fruit->getProtein();
            }

            return [
                'nutrition_facts' => [
                    'calories' => $sumCalories,
                    'fat' => $sumFat,
                    'sugar' => $sumSugar,
                    'carbohydrates' => $sumCarbohydrates,
                    'protein' => $sumProtein,
                ],
            ];
        } else {
            return [
                'nutrition_facts' => [
                    'calories' => 0,
                    'fat' => 0,
                    'sugar' => 0,
                    'carbohydrates' => 0,
                    'protein' => 0,
                ],
            ];
        }
    }
}