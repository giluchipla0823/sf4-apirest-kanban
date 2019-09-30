<?php


namespace App\Repository;

use App\Entity\User;
use App\Helpers\AppHelper;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class UserRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Crear registro de usuario
     *
     * @param array $data
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(array $data): User{
        $container = AppHelper::getKernelContainer();
        $encoder = $container->get('security.password_encoder');

        $user = new User();

        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setUsername($data['username']);
        $user->setPassword($encoder->encodePassword($user, $data['password']));
        $user->setCreatedAt(new \DateTime('now'));
        $user->setUpdatedAt(new \DateTime('now'));

        $this->persistDatabase($user);

        return $user;
    }

    /**
     * Lista de usuarios usando filtros
     *
     * @return mixed
     */
    public function findByCriteria(){
        $query = $this->createQueryBuilder('u')
                      ->getQuery();

        return $query->execute();
    }
}