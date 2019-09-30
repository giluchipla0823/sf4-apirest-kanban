<?php


namespace App\Repository;


use App\Exceptions\EntityNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class BaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    /**
     * Persistir datos un recurso
     *
     * @param $model
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected function persistDatabase($model){
        $em = $this->getEntityManager();
        $em->persist($model);

        return $em->flush();
    }

    /**
     * Obtener los datos de un recurso por su id
     *
     * @param int $id
     * @return object|null
     * @throws EntityNotFoundException
     */
    public function findOrFail(int $id){
        if(!$model = $this->find($id)){
            throw new EntityNotFoundException($this->getClassName());
        }

        return $model;
    }

}