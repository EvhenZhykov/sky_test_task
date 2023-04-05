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

    public function findUniqueStars(string $foundIn, string $notFoundIn, array $atoms, string $sortBy)
    {
        $sortBy = $sortBy === 'size' ? 'radius' : $sortBy;

        $qbNotFoundIn = $this->_em->createQueryBuilder();
        $qbNotFoundIn->select('s')
            ->from(Star::class, 's')
            ->leftJoin('s.atoms', 'a')
            ->where('s.galaxy = :notFoundIn')
            ->setParameter('notFoundIn', $notFoundIn)
        ;

        $result = $qbNotFoundIn->getQuery()->getResult();
        foreach ($result as $key => $star) {
            $atomsValue = [];
            foreach ($star->getAtoms()->toArray() as $atom) {
                $atomsValue[] = $atom->getValue();
            }
            foreach ($atoms as $atom) {
                if (!in_array($atom, $atomsValue)) {
                    unset($result[$key]);
                }
            }
        }

        if (!empty($result)) {
            // if atoms from $atoms are found on another galaxy, return an empty array
            return [];
        }

        $qb = $this->_em->createQueryBuilder();
        $qb->select('s')
            ->from(Star::class, 's')
            ->leftJoin('s.atoms', 'a')
            ->where('s.galaxy = :foundIn')
            ->setParameter('foundIn', $foundIn)
        ;

        $result = $qb->orderBy('s.' . $sortBy, 'ASC')->getQuery()->getResult();

        foreach ($result as $key => $star) {
            $atomsValue = [];
            foreach ($star->getAtoms()->toArray() as $atom) {
                $atomsValue[] = $atom->getValue();
            }
            foreach ($atoms as $atom) {
                if (!in_array($atom, $atomsValue)) {
                    unset($result[$key]);
                }
            }
        }

        return $result;
    }
}
