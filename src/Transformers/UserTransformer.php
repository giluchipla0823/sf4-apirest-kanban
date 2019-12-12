<?php

namespace App\Transformers;

use App\Entity\User;
use App\Libraries\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['boards'];

    public function transform(User $user){
        return [
            "id" => $user->getId(),
            "name" => $user->getName()
        ];
    }

    public function includeBoards(User $user){
        $boards = $user->getBoards();

        return $boards ? $this->collection($boards, new BoardTransformer()) : $this->null();
    }
}