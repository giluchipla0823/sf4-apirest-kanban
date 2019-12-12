<?php

namespace App\Controller;

use App\Entity\Board;
use App\Transformers\BoardTransformer;
use App\Validator\Requests\BoardRequest;
use Doctrine\Bundle\DoctrineBundle\Registry;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Validator\Constraints\Json;
use Tests\Fixtures\Transformer\BookTransformer;

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

        // $boards = $this->repository->findBy(['user' => $user->getId()]);

        $boards = $this->repository->findAll();

        return $this->showCollectionResponse($boards);
    }

    /**
     * Obtener pizarra por su id
     *
     * @param Request $request
     * @param Board $board
     * @Entity("board", expr="repository.findOrFail(id)")
     * @return JsonResponse
     */
    public function showAction(Request $request, Board $board) {
        return $this->showInstanceResponse($board);
    }

    /**
     * Crear pizarra
     *
     * @param BoardRequest $request
     * @return JsonResponse
     */
    public function createAction(BoardRequest $request) {
        $request = $request->getCurrentRequest();
        $request->query->add(['includes' => 'user']);

        $board = $this->repository->create($request->request->all(), $this->getUser());

        return $this->successResponse($this->transformInstance($board),
            'Board created successfully',
            Response::HTTP_CREATED
        );
    }

    /**
     * Actualizar datos de una pizarra
     *
     * @param BoardRequest $request
     * @param Board $board
     * @Entity("board", expr="repository.findOrFail(id)")
     * @return JsonResponse
     */
    public function updateAction(BoardRequest $request, Board $board) {
        $request = $request->getCurrentRequest();
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
