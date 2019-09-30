<?php

namespace App\Controller;

use App\Helpers\AppHelper;
use App\Traits\ApiRequestValidation;
use App\Traits\ApiResponser;
use FOS\RestBundle\Controller\FOSRestController;

class ApiController extends FOSRestController
{
    use ApiResponser, ApiRequestValidation;

    protected $repository;

    public function __construct($entityClass = NULL)
    {
        if($entityClass){
            $container = AppHelper::getKernelContainer();
            $doctrine = $container->get('doctrine');

            $this->repository = $doctrine->getRepository($entityClass);
        }
    }
}
