<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TasksController extends ApiController
{
    /**
     * Crear tarea
     *
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request) {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $task = [];
        $message = "";

        try {
            $code = 201;
            $error = false;
            $title = $request->request->get("title", null);
            $description = $request->request->get("description", null);
            $status = $request->request->get("status", null);
            $priority = $request->request->get("priority", null);
            $boardId= $request->request->get("board_id", null);

            if (!is_null($title) && !is_null($description) && !is_null($status) && !is_null($priority) && !is_null($boardId)) {
                $task = new Task();
                $board = $em->getRepository("App:Board")->find($boardId);
                $task->setBoard($board);
                $task->setTitle($title);
                $task->setDescription($description);
                $task->setStatus($status);
                $task->setPriority($priority);

                $em->persist($task);
                $em->flush();

            } else {
                $code = 500;
                $error = true;
                $message = "An error has occurred trying to add new task - Error: You must to provide all the required fields";
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to add new task - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 201 ? $task : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * Actualizar tarea
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateAction(Request $request, int $id) {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $task = [];
        $message = "";

        try {
            $code = 200;
            $error = false;
            $title = $request->request->get("title", null);
            $description = $request->request->get("description", null);
            $status = $request->request->get("status", null);
            $priority = $request->request->get("priority", null);
            $task = $em->getRepository("App:Task")->find($id);

            if (!is_null($task)) {
                if (!is_null($title)) {
                    $task->setTitle($title);
                }

                if (!is_null($description)) {
                    $task->setDescription($description);
                }

                if (!is_null($status)) {
                    $task->setStatus($status);
                }

                if (!is_null($priority)) {
                    $task->setPriority($priority);
                }

                $em->persist($task);
                $em->flush();

            } else {
                $code = 500;
                $error = true;
                $message = "An error has occurred trying to edit the current task - Error: The task id does not exist";
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to edit the current task - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 200 ? $task : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * Eliminar tarea
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function deleteAction(Request $request, int $id) {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();

        try {
            $code = 200;
            $error = false;
            $task = $em->getRepository("App:Task")->find($id);

            if (!is_null($task)) {
                $em->remove($task);
                $em->flush();

                $message = "The task was removed successfully!";

            } else {
                $code = 500;
                $error = true;
                $message = "An error has occurred trying to remove the currrent task - Error: The task id does not exist";
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to remove the current task - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }
}
