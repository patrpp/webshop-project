<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findByFilters(?string $q, ?string $season, ?int $diameter, ?int $limit, ?int $offset)
    {
        $qb = $this->createQueryBuilder('p');
        if ($q) {
            $qb->andWhere('p.name LIKE :q OR p.description LIKE :q')
                ->setParameter('q', '%' . $q . '%');
        }

        if ($season) {
            $seasonKeywords = [
                'téli' => ['téli', 'winter', 'snow', 'TS', 'polaris'],
                'nyári' => ['nyári', 'summer', 'sun', 'GRNX', 'comfort-life'],
                '4 évszakos' => ['4 évszak', 'CrossClimate', 'all season', 'all-season'],
            ];

            $keywords = $seasonKeywords[$season] ?? [];

            if (!empty($keywords)) {
                $seasonOr = $qb->expr()->orX();
                foreach ($keywords as $index => $keyword) {
                    $seasonOr->add(
                        $qb->expr()->orX(
                            $qb->expr()->like('p.name', ':season_kw_' . $index),
                            $qb->expr()->like('p.description', ':season_kw_' . $index)
                        )
                    );
                    $qb->setParameter('season_kw_' . $index, '%' . $keyword . '%');
                }

                $qb->andWhere($seasonOr);
            }
        }

        if ($diameter) {
            $qb->andWhere('(p.name LIKE :diameter1 OR p.name LIKE :diameter2)')
                ->setParameter('diameter1', '%R' . $diameter . '%')
                ->setParameter('diameter2', '%' . $diameter . '"%');
        }

        return $qb->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getResult();
    }
    public function countByFilters(?string $q, ?string $season, ?int $diameter): int
    {
        $qb = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)');

        if ($q) {
            $qb->andWhere('p.name LIKE :q OR p.description LIKE :q')
                ->setParameter('q', '%' . $q . '%');
        }

        if ($season) {
            $seasonKeywords = [
                'téli' => ['téli', 'winter', 'snow', 'TS', 'polaris'],
                'nyári' => ['nyári', 'summer', 'sun', 'GRNX', 'comfort-life'],
                '4 évszakos' => ['4 évszak', 'CrossClimate', 'all season', 'all-season'],
            ];

            $keywords = $seasonKeywords[$season] ?? [];

            if (!empty($keywords)) {
                $seasonOr = $qb->expr()->orX();
                foreach ($keywords as $index => $keyword) {
                    $seasonOr->add(
                        $qb->expr()->orX(
                            $qb->expr()->like('p.name', ':season_kw_' . $index),
                            $qb->expr()->like('p.description', ':season_kw_' . $index)
                        )
                    );
                    $qb->setParameter('season_kw_' . $index, '%' . $keyword . '%');
                }

                $qb->andWhere($seasonOr);
            }
        }

        if ($diameter) {
            $qb->andWhere('(p.name LIKE :diameter1 OR p.name LIKE :diameter2)')
                ->setParameter('diameter1', '%R' . $diameter . '%')
                ->setParameter('diameter2', '%' . $diameter . '"%');
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
