<?php

namespace App\Controller;

use App\Entity\User;
use App\Exceptions\ValidationException;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class UsersController extends ApiController
{
    public function __construct(Registry $doctrine)
    {
        parent::__construct($doctrine, User::class);
    }

    /**
     * Lista de usuarios
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function indexAction(Request $request){
        $users = $this->repository->findByCriteria();

        return $this->showCollectionResponse($users);
    }

    /**
     * Obtener usuario
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function showAction(Request $request, User $user){
        // $user = $this->repository->findOrFail($id);

        return $this->showInstanceResponse($user);
    }

    /**
     * Registro de usuarios
     *
     * @param Request $request
     * @return JsonResponse
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

        $user = $this->repository->create($requestParams);

        return $this->successResponse($this->transformInstance($user),
            'User created successfully',
            Response::HTTP_CREATED
        );
    }
}
