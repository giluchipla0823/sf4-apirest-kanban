<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Task;
use App\Validator\Requests\TaskRequest;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TasksController extends ApiController
{
    public function __construct(Registry $doctrine)
    {
        parent::__construct($doctrine, Task::class);
    }

    /**
     * Crear tarea
     *
     * @param TaskRequest $request
     * @return JsonResponse
     */
    public function createAction(TaskRequest $request) {
        $request = $request->getCurrentRequest();

        // Obtener la pizarra por su id
        $board = $this->getDoctrine()->getRepository(Board::class)
                    ->findOrFail($request->get('board_id'));

        $task = $this->repository->create($request->request->all(), $board);

        return $this->successResponse(
            $this->serializerInstance($task),
            'Task created successfully',
            Response::HTTP_CREATED
        );
    }

    /**
     * Actualizar tarea
     *
     * @param TaskRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateAction(TaskRequest $request, int $id) {
        $task = $this->repository->findOrFail($id);

        $request = $request->getCurrentRequest();

        $board = $this->getDoctrine()->getRepository(Board::class)
                    ->findOrFail($request->get('board_id'));

        $task = $this->repository->update($request->request->all(), $task, $board);

        return $this->successResponse(
            $this->serializerInstance($task),
            'Task updated successfully'
        );
    }

    /**
     * Eliminar tarea
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function deleteAction(Request $request, int $id) {
        $task = $this->repository->findOrFail($id);

        $this->repository->remove($task);

        return $this->showMessageResponse('Task deleted successfully');
    }
}
