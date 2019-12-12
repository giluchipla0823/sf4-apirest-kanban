<?php

namespace App\Controller;

use App\Traits\ApiResponser;
use App\Traits\ResponseTransformer;
use Doctrine\Bundle\DoctrineBundle\Registry;
use FOS\RestBundle\Controller\FOSRestController;

class ApiController extends FOSRestController
{
    use ApiResponser, ResponseTransformer;

    protected $repository;

    public function __construct(Registry $doctrine, $entityClass = NULL)
    {
        if($entityClass){
            $this->repository = $doctrine->getRepository($entityClass);
        }
    }
}
