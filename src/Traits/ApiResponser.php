<?php

namespace App\Traits;

use App\Libraries\Api;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

Trait ApiResponser{

    /**
     * Construir una respuesta de éxito
     *
     * @param $data
     * @param string $message
     * @param int $code
     * @param array $extras
     * @return JsonResponse
     */
    protected function successResponse($data, string $message = 'OK', int $code = Response::HTTP_OK, array $extras = []){
        return $this->_makeResponse($data, $message, $code, $extras);
    }

    /**
     * Crear respuesta para colecciones de una instancia
     *
     * @param array $data
     * @return JsonResponse
     */
    protected function showCollectionResponse($data){
        if(method_exists($this, 'transformCollection')){
            $data = $this->transformCollection($data);
        }

        return $this->successResponse($data);
    }

    /**
     * Crear respuesta para una instancia
     *
     * @param object $data
     * @return JsonResponse
     */
    protected function showInstanceResponse($data){
        if(method_exists($this, 'transformInstance')){
            $data = $this->transformInstance($data);
        }

        return $this->successResponse($data);
    }

    /**
     * Construir respuesta para mostrar sólo mensajes
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function showMessageResponse(string $message, int $code = Response::HTTP_OK){
        return $this->_makeResponse(NULL, $message, $code);
    }


    /**
     * Construir respuesta de error
     *
     * @param string $message
     * @param int $code
     * @param array $extras
     * @return JsonResponse
     */
    protected function errorResponse(string $message, int $code, array $extras = []){
        return $this->_makeResponse(NULL, $message, $code, $extras);
    }

    /**
     * Retornar respuesta json
     *
     * @param $data
     * @param string $message
     * @param int $code
     * @param array $extras
     * @return JsonResponse
     */
    private function _makeResponse($data, string $message, int $code, array $extras = []){
        $response = (new Api)->makeResponse($data, $message, $code, $extras);

        return new JsonResponse($response, $code);
    }
}