<?php


namespace App\Repository;

use App\Entity\Board;
use Doctrine\Common\Persistence\ManagerRegistry;

class BoardRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Board::class);
    }
}