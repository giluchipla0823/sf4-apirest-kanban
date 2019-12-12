<?php

namespace App\Transformers;

use App\Entity\Board;
use App\Helpers\DateHelper;
use App\Libraries\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\NullResource;

class BoardTransformer extends TransformerAbstract
{

    protected $availableIncludes = ['user'];

    /**
     * Transformar respuesta
     *
     * @param Board $board
     * @return array
     */
    public function transform(Board $board){
        return [
            "id" => $board->getId(),
            "name" => $board->getName(),
            "createdAt" => DateHelper::applyFormatToDatetime($board->getCreatedAt()),
            "updatedAt" => DateHelper::applyFormatToDatetime($board->getCreatedAt()),
        ];
    }

    /**
     * I
     *
     * @param Board $board
     * @return array|Item|NullResource|null
     */
    public function includeUser(Board $board){
        $user = $board->getUser();

        return $user ? $this->item($user, new UserTransformer) : $this->null();
    }
}