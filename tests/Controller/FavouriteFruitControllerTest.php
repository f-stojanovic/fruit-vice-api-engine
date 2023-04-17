<?php

namespace App\Tests\Controller;

use App\Controller\FavouriteFruitController;
use App\Service\FavoriteFruitService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class FavouriteFruitControllerTest extends WebTestCase
{
    private $controller;
    private $mockFavoriteFruitService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockFavoriteFruitService = $this->createMock(FavoriteFruitService::class);

        $container = self::getContainer();
        $this->controller = $container->get(FavouriteFruitController::class);
    }

    public function testFavouriteFruitListReturnsValidResponse(): void
    {
        $response = $this->controller->favouriteFruitList();

        $this->assertNotNull($response);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}