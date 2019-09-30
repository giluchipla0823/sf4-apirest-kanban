<?php

namespace App\Repository;

use App\Entity\Board;
use App\Entity\Task;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class TaskRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * Crear tarea
     *
     * @param array $data
     * @param Board $board
     * @return Task
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(array $data, Board $board): Task{
        $task = new Task();

        $task->setTitle($data['title']);
        $task->setDescription($data['description']);
        $task->setStatus($data['status']);
        $task->setPriority($data['priority']);
        $task->setBoard($board);
        $task->setCreatedAt(new \DateTime('now'));
        $task->setUpdatedAt(new \DateTime('now'));

        $this->persistDatabase($task);

        return $task;
    }

    /**
     * Actualizar tarea
     *
     * @param array $data
     * @param Task $task
     * @param Board $board
     * @return Task
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(array $data, Task $task, Board $board): Task{
        $task->setTitle($data['title'] ?? $task->getTitle());
        $task->setDescription($data['description'] ?? $task->getDescription());
        $task->setStatus($data['status'] ?? $task->getStatus());
        $task->setPriority($data['priority'] ?? $task->getPriority());
        $task->setBoard($board);
        $task->setUpdatedAt(new \DateTime('now'));

        $this->persistDatabase($task);

        return $task;
    }

    /**
     * Eliminar tarea
     *
     * @param Task $task
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Task $task): void {
        $em = $this->getEntityManager();

        $em->remove($task);
        $em->flush();
    }
}