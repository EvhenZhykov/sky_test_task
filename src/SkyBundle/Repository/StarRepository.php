<?php

namespace App\SkyBundle\Repository;

use App\SkyBundle\Entity\Star;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Star>
 *
 * @method Star|null find($id, $lockMode = null, $lockVersion = null)
 * @method Star|null findOneBy(array $criteria, array $orderBy = null)
 * @method Star[]    findAll()
 * @method Star[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Star::class);
    }

    public function add(Star $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Star $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findUniqueStars (string $foundIn, string $notFoundIn, array $atomsList, string $sortBy)
    {
        $sortBy = $sortBy === 'size' ? 'radius' : $sortBy;

        $qb = $this->_em->createQueryBuilder();
        $qb->select('s')
        ->from(Star::class, 's');

        $qb
            ->andWhere('s.galaxy = :foundIn')
            ->setParameter('foundIn', $foundIn);

        foreach ($atomsList as $atom) {
            $qb->andWhere($qb->expr()->like('s.atomsFound', $qb->expr()->literal('%' . $atom . '%')));
        }

        $subQb = $this->_em->createQueryBuilder();
        $subQb->select('s2.atomsFound')
            ->from(Star::class, 's2');

        $subQb
            ->andWhere('s2.galaxy = :notFoundIn');
        $subQb->setParameter('notFoundIn', $notFoundIn);

        foreach ($atomsList as $atom) {
            $subQb->andWhere($subQb->expr()->like('s2.atomsFound', $subQb->expr()->literal('%' . $atom . '%')));
        }

        $subQuery = $subQb->getQuery()->getResult();

        foreach ($subQuery as $test) {
            foreach ($test['atomsFound'] as $t) {
                if (in_array($t, $atomsList)) {
                    $qb->andWhere($qb->expr()->notLike('s.atomsFound', $qb->expr()->literal('%' . $t . '%')));
                }
            }
        }

        return $qb
            ->orderBy('s.'.$sortBy, 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
