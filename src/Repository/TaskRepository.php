<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Common\Persistence\ManagerRegistry;

class TaskRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }
}