<?php

namespace App\Repository;

use App\Entity\Slot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Slot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Slot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Slot[]    findAll()
 * @method Slot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Slot::class);
    }

    public function findCalendarSlotsByPeriod($calendarId, $start = null, $end = null)
    {
        $qb = $this->createQueryBuilder('s')
            ->join('s.calendar', 'c')
            ->andWhere('c.id = :cid')
            ->setParameter('cid', $calendarId);

        if ($start) {
            $qb->andWhere('s.start >= :start')
                ->setParameter('start', $start);
        }
        if ($end) {
            $qb->andWhere('s.end <= :end')
                ->setParameter('end', $end);
        }

        return $qb->getQuery()->getResult();
    }
}
