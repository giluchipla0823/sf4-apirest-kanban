<?php

namespace App\Validator\Requests;

use App\Traits\ApiRequestValidation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class BaseRequest
{
    use ApiRequestValidation;

    private $_request;

    public function __construct(RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();

        $this->_setCurrentRequest($request);

        $this->validate();
    }

    public function validate(){
        $this->validateDataRequest($this->getCurrentRequest()->request->all(), $this->rules());
    }

    public function rules(){
        return [];
    }

    private function _setCurrentRequest(Request $request){
        $this->_request = $request;
    }

    public function getCurrentRequest(): Request{
        return $this->_request;
    }
}