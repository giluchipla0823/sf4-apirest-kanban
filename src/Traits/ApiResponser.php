<?php

namespace App\Traits;

use App\Helpers\AppHelper;
use App\Helpers\JsonResponseHelper;
use JMS\Serializer\SerializationContext;
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
    protected function successResponse($data, $message = 'OK', $code = Response::HTTP_OK, $extras = []){
        return $this->_buildResponse($data, $message, $code, $extras);
    }

    /**
     * Crear respuesta para colecciones de una instancia
     *
     * @param array $data
     * @return JsonResponse
     */
    protected function showCollectionResponse($data){
        $data = $this->serializerCollection($data);

        return $this->successResponse($data);
    }

    /**
     * Crear respuesta para una instancia
     *
     * @param object $data
     * @return JsonResponse
     */
    protected function showInstanceResponse($data){
        $data = $this->serializerInstance($data);

        return $this->successResponse($data);
    }

    /**
     * Serializar los datos de una instancia
     *
     * @param object $instance
     * @return mixed|null
     */
    protected function serializerInstance($instance){
        if(is_null($instance)){
            return NULL;
        }

        return $this->applySerializer($instance, get_class($instance));
    }

    /**
     * Serializar datos de una colección
     *
     * @param array $collection
     * @return array|mixed
     */
    protected function serializerCollection($collection){
        if(count($collection) === 0){
            return array();
        }

        return $this->applySerializer($collection, get_class(current($collection)));
    }

    /**
     * Proceso de serialización de datos
     *
     * @param array|object $data
     * @param string $entityClass
     * @return mixed
     */
    protected function applySerializer($data, string $entityClass){
        $container = AppHelper::getKernelContainer();

        if ($container->has('jms_serializer')) {
            $includes = AppHelper::getIncludeMappingAssociationsToSerialize($entityClass);
            $context = SerializationContext::create()->setGroups($includes);
            $context->setSerializeNull(true);

            $serializer = $container->get('jms_serializer');

            $data = json_decode($serializer->serialize($data, 'json', $context), TRUE);
        }

        return $data;
    }

    /**
     * Construir respuesta para mostrar sólo mensajes
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function showMessageResponse(string $message, int $code = Response::HTTP_OK){
        return $this->_buildResponse(NULL, $message, $code);
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
        return $this->_buildResponse(NULL, $message, $code, $extras);
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
    private function _buildResponse($data, string $message, int $code, array $extras = []){
        $response = JsonResponseHelper::getResponse($data, $message, $code, $extras);

        return new JsonResponse($response, $code);
    }
}