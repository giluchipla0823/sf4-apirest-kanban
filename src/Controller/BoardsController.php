<?php

namespace App\Controller;

use App\Entity\Board;
use App\Validator\Requests\BoardRequest;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BoardsController extends ApiController
{

    public function __construct(Registry $doctrine)
    {
        parent::__construct($doctrine, Board::class);
    }

    /**
     * Lista de pizarras
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request) {
        $user = $this->getUser();

        $boards = $this->repository->findBy(['user' => $user->getId()]);

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
        $board = $this->repository->findOrFail($id);

        return $this->showInstanceResponse($board);
    }

    /**
     * Crear pizarra
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request) {
        $request->query->add(['includes' => 'user']);

        $this->get(BoardRequest::class)->validate();

        $board = $this->repository->create($request->request->all(), $this->getUser());

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
     */
    public function updateAction(Request $request, int $id) {
        $board = $this->repository->findOrFail($id);

        $request->query->add(['includes' => 'user']);

        $this->get(BoardRequest::class)->validate();

        $board = $this->repository->update($request->request->all(), $board);

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
        $board = $this->repository->findOrFail($id);

        $this->repository->remove($board);

        return $this->showMessageResponse('Board deleted successfully');
    }
}
