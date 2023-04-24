<?php

namespace App\Service;

use App\Repository\FruitRepository;

class FavoriteFruitService
{
    public function __construct(
       private readonly FruitRepository $fruitRepository
    ) { }

    /**
     * @param array $favoriteFruits
     * @param int $maxFruits
     * @return void
     */
    public function saveFavouriteFruits(array $favoriteFruits, int $maxFruits = 10): void
    {
        if (count($favoriteFruits) > $maxFruits) {
            throw new \InvalidArgumentException("You can only select up to $maxFruits fruits as favorites.");
        }

        // First clear previously saved favourite fruits
        $this->clearFavouriteFruits();

        foreach ($favoriteFruits as $favoriteFruit) {
            $fruit = $this->fruitRepository->findOneBy(['name' => $favoriteFruit->getName()]);
            if (!$fruit) {
                throw new \InvalidArgumentException("Fruit with name '{$favoriteFruit->getName()}' does not exist.");
            }

            $fruit->setIsFavorite(true);

            $this->fruitRepository->save($fruit, true);
        }
    }

    /**
     * @return void
     */
    public function clearFavouriteFruits(): void
    {
        $favouriteFruits = $this->fruitRepository->findBy(['isFavorite' => true]);

        if (count($favouriteFruits) > 0) {
            foreach ($favouriteFruits as $favouriteFruit) {
                $favouriteFruit->setIsFavorite(false);
            }
        }
    }

    /**
     * @return array|float|int|mixed|string
     */
    public function getAllFavouriteFruits(): mixed
    {
        $allFavouriteFruits = $this->fruitRepository->findBy(['isFavorite' => true]);

        $favoriteFruitIds = array();
        foreach ($allFavouriteFruits as $favoriteFruit) {
            $favoriteFruitIds[] = $favoriteFruit->getId();
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
        $allFavouriteFruits = $this->fruitRepository->findBy(['isFavorite' => true]);

        $favoriteFruitIds = array();
        foreach ($allFavouriteFruits as $favoriteFruit) {
            $favoriteFruitIds[] = $favoriteFruit->getId();
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