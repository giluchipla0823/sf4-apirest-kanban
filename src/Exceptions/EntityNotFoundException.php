<?php


namespace App\Exceptions;


use Symfony\Component\HttpFoundation\Response;
use Throwable;

class EntityNotFoundException extends \Exception
{
    public function __construct($entity, $message = "", $code = Response::HTTP_NOT_FOUND, Throwable $previous = null)
    {
        if(!$message){

            $message = "Not found {$this->transformEntityName($entity)} instance with specified id";
        }

        parent::__construct($message, $code, $previous);
    }

    private function transformEntityName($entity){
        $explodeEntity = explode('\\', $entity);

        return strtolower($explodeEntity[2]);
    }
}