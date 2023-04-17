<?php

namespace App\Controller;

use App\Form\FavoriteFruitType;
use App\Form\FruitFilterType;
use App\Repository\FruitRepository;
use App\Service\FavoriteFruitService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class FruitController extends AbstractController
{
    /**
     * @var FruitRepository
     */
    private FruitRepository $fruitRepository;

    /**
     * @var FavoriteFruitService $favoriteFruitService;
     */
    private FavoriteFruitService $favoriteFruitService;

    public function __construct(
        FruitRepository $fruitRepository,
        FavoriteFruitService $favoriteFruitService
    ) {
        $this->fruitRepository         = $fruitRepository;
        $this->favoriteFruitService    = $favoriteFruitService;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    #[Route('/', name: 'fruit_list')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        $allFruits = $this->fruitRepository->findAllFruitsPaginated($page, $limit);

        $total = $this->fruitRepository->count([]);
        $totalPages = ceil($total / $limit);

        return $this->render('fruit/fruit_list.html.twig', [
            'fruits' => $allFruits,
            'page'   => $page,
            'limit'  => $limit,
            'total'  => $total,
            'totalPages' => $totalPages
        ]);
    }

    #[Route('/filter-fruits', name: 'filter_fruits')]
    public function filterFruits(Request $request): Response
    {
        // Create form for fruit filtering
        $fruitFilterForm = $this->createForm(FruitFilterType::class);
        $fruitFilterForm->handleRequest($request);

        // Get list of fruits based on form submission
        $fruits = [];

        if ($fruitFilterForm->isSubmitted() && $fruitFilterForm->isValid()) {
            $data = $fruitFilterForm->getData();
            $fruits = $this->fruitRepository->findBy([
                'name' => $data->getName(),
                'family' => $data->getFamily(),
            ]);
        }

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

            // Ensure that no more than 10 fruits can be added to favorites
            if (count($favoriteFruits) > 10) {
                $this->addFlash('error', 'You can only select up to 10 fruits as favorites.');
            } else {
                // First clear previously saved favourite fruits
                $this->favoriteFruitService->clearFavouriteFruits();

                // Store favorite fruits
               $this->favoriteFruitService->saveFavouriteFruits($favoriteFruits);

                $this->addFlash('success', 'Added! Check Favourite Fruits page.');
            }
        }

        // Render the filter and favorite forms
        return $this->render('fruit/filter.html.twig', [
            'fruitFilterForm' => $fruitFilterForm->createView(),
            'favoriteFruitForm' => $favoriteFruitForm->createView(),
            'fruits' => $fruits,
        ]);
    }
}
