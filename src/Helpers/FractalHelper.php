<?php

namespace App\Helpers;

use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class FractalHelper
{
    /**
     * Aplicar transformador fractal
     *
     * @param Item|Collection $resource
     * @return mixed
     */
    private static function create($resource){
        $container = AppHelper::getKernelContainer();
        $request = $container->get('request_stack')->getCurrentRequest();

        $fractal = $container->get('sam_j_fractal.manager');

        $fractal->parseIncludes($request->get('includes', ''));

        return $fractal->createData($resource)->toArray()['data'];
    }

    /**
     * Crear recurso de colección de entidades
     *
     * @param array $data
     * @param string $transformer
     * @return mixed
     */
    public static function collection(array $data, string $transformer){
        $resource = new Collection($data, new $transformer);

        return self::create($resource);
    }

    /**
     * Crear recurso de instancia de una entidad
     *
     * @param $data
     * @param string $transformer
     * @return mixed
     */
    public static function item($data, string $transformer){
        $resource = new Item($data, new $transformer);

        return self::create($resource);
    }
}