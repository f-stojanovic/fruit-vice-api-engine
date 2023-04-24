<?php

namespace App\Controller;

use App\Form\FavoriteFruitType;
use App\Repository\FruitRepositoryInterface;
use App\Service\FavoriteFruitService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavouriteFruitController extends AbstractController
{
    public function __construct(
       private readonly FavoriteFruitService     $favoriteFruitService,
       private readonly FruitRepositoryInterface $fruitRepository
    ) { }

    /**
     * @throws Exception
     */
    #[Route('/favourite-fruit/list', name: 'favourite_fruit_list')]
    public function favouriteFruitList(): Response
    {
        $allFavouriteFruits = $this->favoriteFruitService->getAllFavouriteFruits();
        $allNutritionFacts  = $this->favoriteFruitService->getSumOfNutritionFacts();

        return $this->render('favourite_fruit/favourite_fruit_list.html.twig', [
            'favouriteFruits'   => $allFavouriteFruits,
            'allNutritionFacts' => $allNutritionFacts
        ]);
    }

    #[Route('/add-favorite-fruits', name: 'add_favorite_fruits')]
    public function addFavoriteFruits(Request $request, FavoriteFruitService $favoriteFruitService): Response
    {
        // Get list of all fruits for favorite form
        $allFruits = $this->fruitRepository->findAll();

        // Create form for favorite fruits
        $favoriteFruitForm = $this->createForm(FavoriteFruitType::class, null, [
            'fruits' => $allFruits
        ]);
        $favoriteFruitForm->handleRequest($request);

        // Add selected fruits to favorites
        if ($favoriteFruitForm->isSubmitted() && $favoriteFruitForm->isValid()) {
            $favoriteFruits = $favoriteFruitForm->get("name")->getData();
            try {
                $favoriteFruitsArray = $favoriteFruits->toArray();
                $favoriteFruitService->saveFavouriteFruits($favoriteFruitsArray);
                $this->addFlash('success', 'Added! Check Favorite Fruits page.');
            } catch (\InvalidArgumentException $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        // Render the favorite fruits form
        return $this->render('favourite_fruit/add_favorite_fruits.html.twig', [
            'favoriteFruitForm' => $favoriteFruitForm->createView(),
        ]);
    }
}