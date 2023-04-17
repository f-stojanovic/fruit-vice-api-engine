<?php

namespace App\Service;

use App\Entity;
use App\Exception\FruityViceApiException;
use App\Repository\FruitRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Throwable;

class FruityViceApiService
{
    /**
     * @var string
     */
    private string $fruityViceApiEndpoint;

    /**
     * @var FruitRepository
     */
    private FruitRepository $fruitRepository;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    public function __construct(
        $fruityViceApiEndpoint,
        FruitRepository $fruitRepository,
        LoggerInterface $logger
    ) {
        $this->fruityViceApiEndpoint = $fruityViceApiEndpoint;
        $this->fruitRepository       = $fruitRepository;
        $this->logger                = $logger;
    }

    /**
     * @return void
     * @throws FruityViceApiException
     */
    public function populateFruitsTable(): void
    {
        $fruits = $this->getAllFruits();

        foreach ($fruits as $item) {

            $fruit = new Entity\Fruit();

            $fruit->setName($item['name']);
            $fruit->setFamily($item['family']);
            $fruit->setOrder($item['order']);
            $fruit->setGenus($item['genus']);

            $nutritions = $item['nutritions'];
            $fruit->setCalories($nutritions['calories']);
            $fruit->setFat($nutritions['fat']);
            $fruit->setSugar($nutritions['sugar']);
            $fruit->setCarbohydrates($nutritions['carbohydrates']);
            $fruit->setProtein($nutritions['protein']);

            $this->fruitRepository->save($fruit, true);
        }
    }

    /**
     * @return array
     * @throws FruityViceApiException
     */
    public function getAllFruits(): array
    {
        try {
            $response = $this->request('GET', '/api/fruit/all', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-type' => 'application/json'
                ],
            ]);
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());
            throw new FruityViceApiException("Url not found");
        }
        return $response;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @return array
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function request(string $method, string $url, array $options = []): array
    {
        $response = HttpClient::create(['base_uri' => $this->fruityViceApiEndpoint])->request($method, $url, $options);
        return json_decode($response->getContent(false), true) ?? [];
    }
}