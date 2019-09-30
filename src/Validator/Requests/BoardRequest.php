<?php

namespace App\Validator\Requests;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints as Assert;

class BoardRequest extends BaseRequest
{

    public function __construct(RequestStack $requestStack)
    {
        parent::__construct($requestStack);
    }

    public function rules(){
        $method = $this->request->getMethod();

        switch ($method){
            case 'PUT':
                    return $this->_rules();
                break;
            case 'POST':
                    return $this->_rules();
                break;
            default:
                break;
        }
    }

    private function _rules(){
        return [
            'name' => [
                new Assert\NotBlank()
            ]
        ];
    }
}