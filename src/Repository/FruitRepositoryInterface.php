<?php

namespace App\Repository;

use App\Entity;
interface FruitRepositoryInterface
{
    public function findAllFruitsPaginated(int $page = 1, int $limit = 10): array;
    public function findByName(string $name): ?Entity\Fruit;
    public function save(Entity\Fruit $fruit, bool $flush = false): void;
    public function remove(Entity\Fruit $fruit, bool $flush = false): void;
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    public function count(array $criteria);
}