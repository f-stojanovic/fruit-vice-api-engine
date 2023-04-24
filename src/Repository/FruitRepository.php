<?php

namespace App\Repository;

use App\Entity\Fruit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @extends ServiceEntityRepository<Fruit>
 *
 * @method Fruit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fruit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fruit[]    findAll()
 * @method Fruit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FruitRepository extends ServiceEntityRepository implements FruitRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fruit::class);
    }

    /**
     * @param int $page
     * @param int $limit
     * @return array
     * @throws Exception
     */
    public function findAllFruitsPaginated(int $page = 1, int $limit = 10): array
    {
        $query = $this->createQueryBuilder('f')
            ->orderBy('f.name', 'ASC')
            ->getQuery();

        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return $paginator->getIterator()->getArrayCopy();
    }

    public function findByName(string $name): ?Fruit
    {
        return $this->findOneBy(['name' => $name]);
    }

    public function save(Fruit $fruit, bool $flush = false): void
    {
        $this->getEntityManager()->persist($fruit);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Fruit $fruit, bool $flush = false): void
    {
        $this->getEntityManager()->remove($fruit);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}