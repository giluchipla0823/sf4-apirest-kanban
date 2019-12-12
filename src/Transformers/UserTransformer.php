<?php

namespace App\Transformers;

use App\Entity\User;
use App\Helpers\DateHelper;
use App\Libraries\TransformerAbstract;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\NullResource;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['boards'];

    /**
     * Transformar respuesta
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user){
        return [
            "id" => (int) $user->getId(),
            "name" => (string) $user->getName(),
            "email" => (string) $user->getEmail(),
            "username" => (string) $user->getUsername(),
            "createdAt" => DateHelper::applyFormatToDatetime($user->getCreatedAt()),
            "updatedAt" => DateHelper::applyFormatToDatetime($user->getCreatedAt()),
        ];
    }

    /**
     * Incluir pizarras
     *
     * @param User $user
     * @return Collection
     */
    public function includeBoards(User $user){
        $boards = $user->getBoards();

        return $this->collection($boards, new BoardTransformer());
    }
}