<?php

namespace App\Traits;

use App\Exceptions\ValidationException;
use App\Helpers\AppHelper;
use App\Helpers\ArrayHelper;
use Symfony\Component\Validator\Constraints as Assert;

Trait ApiRequestValidation{

    /**
     * Validar datos de las peticiones
     *
     * @param array $data
     * @param array $constraints
     * @throws ValidationException
     */
    public function validateDataRequest(array $data, array $constraints){
        $container = AppHelper::getKernelContainer();
        $validator = $container->get('validator');

        $data = ArrayHelper::onlyItems($data, array_keys($constraints));

        $errors = $validator->validate($data, new Assert\Collection($constraints));

        if(count($errors) > 0){
            throw new ValidationException($errors);
        }
    }
}
