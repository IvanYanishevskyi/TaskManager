<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findByUserId(int $userId): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.users_id = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('t.create_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findByDate($userId, $date)
    {
        return $this->createQueryBuilder('t')
            ->where('t.usersId = :userId')
            ->andWhere('t.date = :date')
            ->setParameter('userId', $userId)
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }
    public function findByNotDate($userId, $date)
    {
        return $this->createQueryBuilder('t')
            ->where('t.usersId = :userId')
            ->andWhere('t.date != :date OR t.date IS NULL')
            ->setParameter('userId', $userId)
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }
}
