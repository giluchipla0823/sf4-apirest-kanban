<?php


namespace App\Validator\Requests;

use App\Traits\ApiRequestValidation;
use Symfony\Component\HttpFoundation\RequestStack;

class BaseRequest
{
    use ApiRequestValidation;

    protected $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function validate(){
        $this->validateDataRequest($this->request->request->all(), $this->rules());
    }

    public function rules(){
        return [];
    }
}