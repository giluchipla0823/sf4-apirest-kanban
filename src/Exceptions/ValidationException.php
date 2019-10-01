<?php

namespace App\Exceptions;

use App\Helpers\ValidationHelper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends \Exception
{

    /**
     * Lista de errores
     *
     * @var array $errors
     */
    protected $errors;

    public function __construct(ConstraintViolationListInterface $errors, $code = Response::HTTP_UNPROCESSABLE_ENTITY, $message = "Validation failed", \Throwable $previous = null)
    {
        $this->setErrors($errors);

        parent::__construct($message, $code, $previous);
    }

    /**
     * Obtener errores de validación
     *
     * @return array
     */
    public function getErrors(){
        return $this->errors;
    }

    /**
     * Asignar y aplicar formato a errores de validación
     *
     * @param ConstraintViolationListInterface $errors
     */
    public function setErrors(ConstraintViolationListInterface $errors) {
        $this->errors = ValidationHelper::transformValidatorErrors($errors);
    }
}