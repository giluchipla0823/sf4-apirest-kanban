<?php


namespace App\Repository;

use App\Entity\Board;
use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class BoardRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Board::class);
    }

    /**
     * Crear registro de pizarra
     *
     * @param array $data
     * @param User $user
     * @return Board
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(array $data, User $user): Board{
        $board = new Board();

        $board->setName($data['name']);
        $board->setUser($user);
        $board->setCreatedAt(new \DateTime('now'));
        $board->setUpdatedAt(new \DateTime('now'));

        $this->persistDatabase($board);

        return $board;
    }

    /**
     * Actualizar pizarra
     *
     * @param array $data
     * @param Board $board
     * @return Board
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(array $data, Board $board): Board{
        $board->setName($data['name'] ?? $board->getName());
        $board->setUpdatedAt(new \DateTime('now'));

        $this->persistDatabase($board);

        return $board;
    }

    /**
     * Eliminar pizarra
     *
     * @param Board $board
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Board $board): void {
        $em = $this->getEntityManager();

        $em->remove($board);
        $em->flush();
    }
}