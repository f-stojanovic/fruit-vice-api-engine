<?php

namespace App\Controller;

use App\Form\FavoriteFruitType;
use App\Form\FruitFilterType;
use App\Repository\FruitRepositoryInterface;
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
    public function __construct(
       private readonly FruitRepositoryInterface $fruitRepository,
    ) { }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    #[Route('/', name: 'fruit_list')]
    public function listFruits(Request $request): Response
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

        // Render the filter form and filtered fruits
        return $this->render('fruit/filter.html.twig', [
            'fruitFilterForm' => $fruitFilterForm->createView(),
            'fruits' => $fruits,
        ]);
    }
}
