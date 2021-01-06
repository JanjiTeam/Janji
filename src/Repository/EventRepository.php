<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findCalendarEventsByPeriod($calendarId, $start = null, $end = null, $free = false)
    {
        $qb = $this->createQueryBuilder('e')
            ->join('e.calendar', 'c')
            ->leftJoin('e.user', 'u')
            ->andWhere('c.id = :cid')
            ->setParameter('cid', $calendarId);

        if ($start) {
            $qb->andWhere('e.start >= :start')
                ->setParameter('start', $start);
        }
        if ($end) {
            $qb->andWhere('e.end <= :end')
                ->setParameter('end', $end);
        }

        if ($free) {
            $qb->andWhere('u.id IS NULL');
        }

        return $qb->getQuery()->getResult();
    }

    public function findOverlappingEvents($calendarId, $start, $end)
    {
        $qb = $this->createQueryBuilder('e')
            ->join('e.calendar', 'c')
            ->andWhere('c.id = :cid')
            ->setParameter('cid', $calendarId)
            ->where('e.start >= :start AND e.start < :end')
            ->orWhere(':start >= e.start AND :start < e.end')
            ->setParameters([
                'start' => $start,
                'end' => $end,
            ]);

        return $qb->getQuery()->getResult();
    }

    public function findUserFutureEvents($userId)
    {
        $qb = $this->createQueryBuilder('e')
            ->join('e.user', 'u')
            ->andWhere('e.start >= :now')
            ->andWhere('u.id = :uid')
            ->setParameter('now', new \DateTimeImmutable('now'))
            ->setParameter('uid', $userId);

        return $qb->getQuery()->getResult();
    }
}
