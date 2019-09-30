<?php

namespace App\Controller;

use App\Traits\ApiRequestValidation;
use App\Traits\ApiResponser;
use Doctrine\Bundle\DoctrineBundle\Registry;
use FOS\RestBundle\Controller\FOSRestController;

class ApiController extends FOSRestController
{
    use ApiResponser;

    protected $repository;

    public function __construct(Registry $doctrine, $entityClass = NULL)
    {
        if($entityClass){
            $this->repository = $doctrine->getRepository($entityClass);
        }
    }
}
