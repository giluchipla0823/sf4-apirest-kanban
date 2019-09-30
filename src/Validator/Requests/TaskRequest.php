<?php


namespace App\Validator\Requests;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints as Assert;

class TaskRequest extends BaseRequest
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
            'title' => [
                new Assert\NotBlank()
            ],
            'description' => [
                new Assert\NotBlank()
            ],
            'status' => [
                new Assert\NotBlank(),
                new Assert\Choice(['Backlog', 'Working', 'Done'])
            ],
            'priority' => [
                new Assert\NotBlank(),
                new Assert\Choice(["High", "Medium", "Low"])
            ],
            'board_id' => [
                new Assert\NotBlank()
            ]
        ];
    }
}