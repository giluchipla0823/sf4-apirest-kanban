<?php

namespace App\Traits;

use App\Helpers\AppHelper;
use App\Helpers\FractalHelper;
use JMS\Serializer\SerializationContext;

Trait ResponseTransformer{

    /**
     * Transformar respuesta de una colección
     *
     * @param array $collection
     * @return array|mixed
     */
    protected function transformCollection(array $collection){
        if(count($collection) === 0){
            return [];
        }

        $first = current($collection);
        $transformer = property_exists($first, 'transformer') ? $first->transformer : NULL;

        if($transformer){
            $collection = FractalHelper::collection($collection, $transformer);
        }else{
            $collection = $this->applySerializer($collection, get_class($first));
        }

        return $collection;
    }

    /**
     * Transformar respuesta de una instancia
     *
     * @param null|object $instance
     * @return mixed
     */
    protected function transformInstance($instance){
        if(!$instance){
            return NULL;
        }

        $transformer = property_exists($instance, 'transformer') ? $instance->transformer : NULL;

        if($transformer){
            $instance = FractalHelper::item($instance, $transformer);
        }else{
            $instance = $this->applySerializer($instance, get_class($instance));
        }

        return $instance;
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
            $context = SerializationContext::create()
                            ->setGroups($includes);

            $context->setSerializeNull(true);

            $serializer = $container->get('jms_serializer');

            $data = json_decode($serializer->serialize($data, 'json', $context), TRUE);
        }

        return $data;
    }
}
