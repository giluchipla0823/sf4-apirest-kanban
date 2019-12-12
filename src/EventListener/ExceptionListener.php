<?php

namespace App\EventListener;

use Adamsafr\FormRequestBundle\Exception\FormValidationException;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\ValidationException;
use App\Helpers\AppHelper;
use App\Helpers\ApiHelper;
use App\Traits\ApiResponser;
use Doctrine\DBAL\Exception\ConnectionException as DBALConnectionException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ExceptionListener
{
    use ApiResponser;

    /**
     * Detector de excepciones personalizado
     *
     * @param GetResponseForExceptionEvent $event
     * @throws \Exception
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $container = AppHelper::getKernelContainer();

        $exception = $event->getException();

        if($container->getParameter('exception_view')){
            throw $exception;
        }

        if($exception instanceof NotFoundHttpException){
            return $this->getResponseHttpNotFoundException($event, $exception);
        }

        if($exception instanceof ValidationException){
            return $this->getResponseValidatorException($event, $exception);
        }

        if($exception instanceof DBALConnectionException){
            return $this->getResponseDBALConnectionException($event, $exception);
        }

        $code = $exception->getCode();

        if(method_exists($exception, 'getStatusCode')){
            $code = $exception->getStatusCode();
        }

        try {
            $json = $this->errorResponse($exception->getMessage(), $code);
        }catch (\Exception $exc){
            $json = $this->errorResponse('Error inesperado('. $exc->getCode() .'): ' . $exc->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $event->setResponse($json);
    }

    public function getResponseHttpNotFoundException(GetResponseForExceptionEvent $event, NotFoundHttpException $exception){
        if(!$exception->getPrevious() instanceof ResourceNotFoundException){
            $model = str_replace(" object not found by the @ParamConverter annotation.", "", $exception->getMessage());

            $exception = new EntityNotFoundException($model);
        }

        $json = $this->errorResponse($exception->getMessage(), Response::HTTP_NOT_FOUND);

        return $event->setResponse($json);
    }

    /**
     * Obtener respuesta para errores de valicación de campos
     *
     * @param GetResponseForExceptionEvent $event
     * @param ValidationException $exception
     */
    public function getResponseValidatorException(GetResponseForExceptionEvent $event, ValidationException $exception){
        $code = $exception->getCode();
        $message = $exception->getMessage();
        $errors = $exception->getErrors();

        $json = $this->errorResponse($message, $code, [ApiHelper::IDX_JSON_ERRORS => $errors]);

        return $event->setResponse($json);
    }

    /**
     * Resolver respuesta para errores de base de datos
     *
     * @param GetResponseForExceptionEvent $event
     * @param DBALConnectionException $exception
     */
    public function getResponseDBALConnectionException(GetResponseForExceptionEvent $event, DBALConnectionException $exception){
        $message = "Error {$exception->getErrorCode()}: {$exception->getMessage()}";

        $json = $this->errorResponse($message, Response::HTTP_INTERNAL_SERVER_ERROR);

        return $event->setResponse($json);
    }
}