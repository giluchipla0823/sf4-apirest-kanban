<?php

namespace App\Controller;

use App\Entity\Board;
use App\Exceptions\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

class BoardsController extends ApiController
{
    /**
     * Lista de pizarras
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request) {
        $user = $this->getUser();

        $boards = $this->getDoctrine()->getRepository(Board::class)
                        ->findBy(['user' => $user->getId()]);

        return $this->showCollectionResponse($boards);
    }

    /**
     * Obtener pizarra por su id
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function showAction(Request $request, $id) {
        $board = $this->getDoctrine()->getRepository(Board::class)
            ->findOrFail($id);

        return $this->showInstanceResponse($board);
    }

    /**
     * Crear pizarra
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function createAction(Request $request) {
        $request->query->add(['includes' => 'user']);
        $requestParams = $request->request->all();

        $constraints = [
            'name' => [
                new Assert\NotBlank()
            ]
        ];

        $this->validateDataRequest($requestParams, $constraints);

        $board = $this->getDoctrine()
            ->getRepository(Board::class)
            ->create($requestParams, $this->getUser());

        return $this->successResponse($this->serializerInstance($board),
            'Board created successfully',
            Response::HTTP_CREATED
        );
    }

    /**
     * Actualizar datos de una pizarra
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function updateAction(Request $request, int $id) {
        $repository = $this->getDoctrine()->getRepository(Board::class);

        $board = $repository->findOrFail($id);

        $request->query->add(['includes' => 'user']);
        $requestParams = $request->request->all();

        $constraints = [
            'name' => [
                new Assert\NotBlank()
            ]
        ];

        $this->validateDataRequest($requestParams, $constraints);

        $board = $repository->update($requestParams, $board);

        return $this->successResponse(
            $this->serializerInstance($board),
            'Board updated successfully'
        );
    }

    /**
     * Eliminar pizarra por su id
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function deleteAction(Request $request, int $id) {
        $repository = $this->getDoctrine()->getRepository(Board::class);

        $board = $repository->findOrFail($id);

        $repository->remove($board);

        return $this->showMessageResponse('Board deleted successfully');
    }
}
