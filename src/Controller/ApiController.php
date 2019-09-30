<?php

namespace App\Controller;

use App\Traits\ApiRequestValidation;
use App\Traits\ApiResponser;
use FOS\RestBundle\Controller\FOSRestController;

class ApiController extends FOSRestController
{
    use ApiResponser, ApiRequestValidation;
}
