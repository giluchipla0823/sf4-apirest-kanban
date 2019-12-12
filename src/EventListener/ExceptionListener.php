<?php

namespace App\EventListener;

use Adamsafr\FormRequestBundle\Exception\FormValidationException;
use App\Exceptions\ValidationException;
use App\Helpers\AppHelper;
use App\Helpers\ApiHelper;
use App\Helpers\ValidationHelper;
use App\Traits\ApiResponser;
use Doctrine\DBAL\Exception\ConnectionException as DBALConnectionException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

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

        dump($exception);
        exit();

        if($container->getParameter('exception_view')){
            throw $exception;
        }

        if($exception instanceof FormValidationException){
            throw new ValidationException($exception->getViolations());
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

        $code = $code > 0 ? $code : Response::HTTP_INTERNAL_SERVER_ERROR;

        $message = $exception->getMessage();

        $response = $this->errorResponse($message, $code);

        return $event->setResponse($response);
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

        $response = $this->errorResponse($message, $code, [ApiHelper::IDX_JSON_ERRORS => $errors]);

        return $event->setResponse($response);
    }

    /**
     * Resolver respuesta para errores de base de datos
     *
     * @param GetResponseForExceptionEvent $event
     * @param DBALConnectionException $exception
     */
    public function getResponseDBALConnectionException(GetResponseForExceptionEvent $event, DBALConnectionException $exception){
        $message = "Error {$exception->getErrorCode()}: {$exception->getMessage()}";

        return $event->setResponse($this->errorResponse($message, Response::HTTP_INTERNAL_SERVER_ERROR));
    }
}