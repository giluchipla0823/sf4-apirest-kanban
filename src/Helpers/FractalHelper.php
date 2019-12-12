<?php


namespace App\Helpers;


use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class FractalHelper
{
    private static function create($resource){
        $container = AppHelper::getKernelContainer();
        $request = $container->get('request_stack')->getCurrentRequest();

        $fractal = $container->get('sam_j_fractal.manager');

        $fractal->parseIncludes($request->get('includes', ''));

        return $fractal->createData($resource)->toArray()['data'];
    }

    public static function collection($data, $transformer){
        $resource = new Collection($data, new $transformer);

        return self::create($resource);
    }

    public static function item($data, $transformer){
        $resource = new Item($data, new $transformer);

        return self::create($resource);
    }
}