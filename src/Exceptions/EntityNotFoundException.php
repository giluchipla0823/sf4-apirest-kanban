<?php


namespace App\Exceptions;


use Symfony\Component\HttpFoundation\Response;
use Throwable;

class EntityNotFoundException extends \Exception
{
    public function __construct($entity, $message = "", $code = Response::HTTP_NOT_FOUND, Throwable $previous = null)
    {
        if(!$message){

            $message = "No existe una instancia de {$this->transformEntityName($entity)} con el id especificado";
        }

        parent::__construct($message, $code, $previous);
    }

    private function transformEntityName($entity){
        $explodeEntity = explode('\\', $entity);

        return strtolower($explodeEntity[2]);
    }
}