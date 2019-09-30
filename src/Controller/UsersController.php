<?php

namespace App\Controller;

use App\Entity\User;
use App\Exceptions\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class UsersController extends ApiController
{
    /**
     * Lista de usuarios
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function indexAction(Request $request){
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findByCriteria();

        return $this->showCollectionResponse($users);
    }

    /**
     * Obtener usuario
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function showAction(Request $request, int $id){
        $user = $this->getDoctrine()->getRepository(User::class)->findOrFail($id);

        return $this->showInstanceResponse($user);
    }

    /**
     * Registro de usuarios
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function createAction(Request $request) {
        $requestParams = $request->request->all();

        $constraints = [
            'name' => [
                new Assert\NotBlank()
            ],
            'email' => [
                new Assert\NotBlank(),
                new Assert\Email()
            ],
            'username' => [
                new Assert\NotBlank()
            ],
            'password' => [
                new Assert\NotBlank()
            ],
        ];

        $this->validateDataRequest($requestParams, $constraints);

        $user = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->create($requestParams);

        return $this->successResponse($this->serializerInstance($user),
            'User created successfully',
            Response::HTTP_CREATED
        );
    }
}
