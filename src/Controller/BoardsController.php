<?php

namespace App\Controller;

use App\Entity\Board;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BoardsController extends ApiController
{
    /**
     * Lista de pizarras
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request) {
        $serializer = $this->get('jms_serializer');

        $em = $this->getDoctrine()->getManager();
        $boards = [];
        $message = "";

        try {
            $code = 200;
            $error = false;

            $userId = $this->getUser()->getId();
            $boards = $em->getRepository("App:Board")->findBy([
                "user" => $userId,
            ]);

            if (is_null($boards)) {
                $boards = [];
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to get all Boards - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 200 ? $boards : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * Obtener pizarra por su id
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function showAction(Request $request, $id) {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $board = [];
        $message = "";

        try {
            $code = 200;
            $error = false;

            $board_id = $id;
            $board = $em->getRepository("App:Board")->find($board_id);

            if (is_null($board)) {
                $code = 500;
                $error = true;
                $message = "The board does not exist";
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to get the current Board - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 200 ? $board : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * Crear pizarra
     *
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request) {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $board = [];
        $message = "";

        try {
            $code = 201;
            $error = false;
            $name = $request->request->get("name", null);
            $user = $this->getUser();

            if (!is_null($name)) {
                $board = new Board();
                $board->setName($name);
                $board->setUser($user);

                $em->persist($board);
                $em->flush();

            } else {
                $code = 500;
                $error = true;
                $message = "An error has occurred trying to add new board - Error: You must to provide a board name";
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to add new board - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 201 ? $board : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * Actualizar datos de una pizarra
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateAction(Request $request, int $id) {
        $serializer = $this->get('jms_serializer');
        $em = $this->getDoctrine()->getManager();
        $board = [];
        $message = "";

        try {
            $code = 200;
            $error = false;
            $name = $request->request->get("name", null);
            $board = $em->getRepository("App:Board")->find($id);

            if (!is_null($name) && !is_null($board)) {
                $board->setName($name);

                $em->persist($board);
                $em->flush();

            } else {
                $code = 500;
                $error = true;
                $message = "An error has occurred trying to add new board - Error: You must to provide a board name or the board id does not exist";
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to edit the current board - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 200 ? $board : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * Eliminar pizarra por su id
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
            $board = $em->getRepository("App:Board")->find($id);

            if (!is_null($board)) {
                $em->remove($board);
                $em->flush();

                $message = "The board was removed successfully!";

            } else {
                $code = 500;
                $error = true;
                $message = "An error has occurred trying to remove the currrent board - Error: The board id does not exist";
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to remove the current board - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }
}
