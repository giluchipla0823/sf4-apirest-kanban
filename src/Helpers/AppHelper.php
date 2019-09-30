<?php

namespace App\Helpers;


class AppHelper
{
    public static function getKernelContainer(){
        global $kernel;

        return $kernel->getContainer();
    }

    public static function getIncludeMappingAssociationsToSerialize(string $entityClass): array {
        $container = self::getKernelContainer();

        $manager = $container->get('doctrine')->getManagerForClass($entityClass);
        $classMetadata = $manager->getClassMetadata($entityClass);
        $relations = $classMetadata->getAssociationNames();

        $requestStack = $container->get('request_stack');
        $request = $requestStack->getCurrentRequest();

        $output = array('Default');
        $includes = $request->get('includes');

        if($includes){
            $includes = explode(',', $includes);

            foreach ($includes as $include){
                if(in_array($include, $relations)){
                    $output[] = $include;
                }
            }
        }

        return $output;
    }
}