<?php

namespace App\Service;

use App\Exception\FruityViceApiException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class FruityViceApiService
{
    public function __construct(
        private readonly string                   $fruityViceApiEndpoint,
        private readonly HttpClientInterface      $httpClient,
        private readonly LoggerInterface          $logger
    ) { }

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
        } catch (ExceptionInterface $e) {
            $this->logger->error($e->getMessage());
            throw new FruityViceApiException('Failed to fetch fruits', 0, $e);
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
     * @throws DecodingExceptionInterface
     */
    private function request(string $method, string $url, array $options = []): array
    {
        $response = $this->httpClient->request($method, $this->fruityViceApiEndpoint . $url, $options);
        return $response->toArray() ?? [];
    }
}