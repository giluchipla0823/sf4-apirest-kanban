<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends ApiController
{
    /**
     * Servicio de prueba
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function testAction(Request $request){
        return $this->showMessageResponse('test action');
    }
}

