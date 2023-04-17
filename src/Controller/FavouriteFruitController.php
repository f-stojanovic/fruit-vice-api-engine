<?php

namespace App\Controller;

use App\Service\FavoriteFruitService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavouriteFruitController extends AbstractController
{
    /**
     * @var FavoriteFruitService
     */
    private FavoriteFruitService $favoriteFruitService;


    public function __construct(
        FavoriteFruitService $favoriteFruitService
    ) {
        $this->favoriteFruitService = $favoriteFruitService;
    }

    /**
     * @throws Exception
     */
    #[Route('/favourite-fruit/list', name: 'favourite_fruit_list')]
    public function favouriteFruitList(): Response
    {
        $allFavouriteFruits = $this->favoriteFruitService->getAllFavouriteFruits();
        $allNutritionFacts = $this->favoriteFruitService->getSumOfNutritionFacts();

        return $this->render('favourite_fruit/favourite_fruit_list.html.twig', [
            'favouriteFruits'   => $allFavouriteFruits,
            'allNutritionFacts' => $allNutritionFacts
        ]);
    }
}