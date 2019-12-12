<?php
namespace App\Libraries;

use League\Fractal\Resource\Primitive;
use League\Fractal\Scope;
use League\Fractal\TransformerAbstract as FractalTransformerAbstract;

abstract class TransformerAbstract extends FractalTransformerAbstract
{

    private $transformMethodApplied = 'item';

    protected function collection($data, $transformer, $resourceKey = null)
    {
        $this->transformMethodApplied = 'collection';
        return parent::collection($data, $transformer, $resourceKey);
    }

    protected function null()
    {
        return $this->transformMethodApplied === 'collection' ? [] : NULL;
    }

    /**
     * This method is fired to loop through available includes, see if any of
     * them are requested and permitted for this scope.
     *
     * @internal
     *
     * @param Scope $scope
     * @param mixed $data
     * @return array|bool
     * @throws \Exception
     */
    public function processIncludedResources(Scope $scope, $data)
    {
        $includedData = [];

        $includes = $this->figureOutWhichIncludes($scope);

        foreach ($includes as $include) {
            $includedData = $this->includeResourceIfAvailable(
                $scope,
                $data,
                $includedData,
                $include
            );
        }

        return $includedData === [] ? false : $includedData;
    }

    /**
     * Figure out which includes we need.
     *
     * @internal
     *
     * @param Scope $scope
     *
     * @return array
     */
    private function figureOutWhichIncludes(Scope $scope)
    {
        $includes = $this->getDefaultIncludes();

        foreach ($this->getAvailableIncludes() as $include) {
            if ($scope->isRequested($include)) {
                $includes[] = $include;
            }
        }

        foreach ($includes as $include) {
            if ($scope->isExcluded($include)) {
                $includes = array_diff($includes, [$include]);
            }
        }

        return $includes;
    }

    /**
     * Include a resource only if it is available on the method.
     *
     * @internal
     *
     * @param Scope $scope
     * @param mixed $data
     * @param array $includedData
     * @param string $include
     * @return array
     * @throws \Exception
     */
    private function includeResourceIfAvailable(
        Scope $scope,
        $data,
        $includedData,
        $include
    ) {
        if ($resource = $this->callIncludeMethod($scope, $include, $data)) {
            $childScope = $scope->embedChildScope($include, $resource);

            if ($childScope->getResource() instanceof Primitive) {
                $includedData[$include] = $childScope->transformPrimitiveResource();
            } else {
                $includedData[$include] = $childScope->toArray()['data'];
            }
        }else{
            $includedData[$include] = $this->null();
        }

        return $includedData;
    }
}