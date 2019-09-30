<?php


namespace App\Helpers;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationHelper
{
    /**
     * Aplicar formato a los errores de validación
     *
     * @param ConstraintViolationListInterface $errors
     * @return array
     */
    public static function transformValidatorErrors(ConstraintViolationListInterface $errors)
    {
        $response = [];

        foreach ($errors as $error) {
            $key = str_replace(array('[', ']'), '', $error->getPropertyPath());
            $response[] = array(
                'field' => $key,
                'message' => $error->getMessage()
            );
        }

        return $response;
    }
}