<?php

namespace App\Controller;

use App\Model\Game;
use App\Service\GameService;
use App\Validator\RequestValidator;
use App\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ApiController
 * @package App\Controller
 *
 * @Route("/api")
 *
 * @author Nazar Salo <salo.nazar@gmail.com>
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/action", name="api_action", methods={"POST"})
     *
     * @param Request $request
     * @param GameService $game
     *
     * @return JsonResponse
     * @throws BadRequestException
     */
    public function action(Request $request, GameService $game): JsonResponse
    {
        $data = $request->getContent();
        $validator = new RequestValidator();
        $validator->isValid($data);
        $data = json_decode($data);
        $next = $game->action($data->state);

        $response = [
            'state' => $data->state,
            'botAction' => $next
        ];

        $game = new Game($data->state, $next);
        if ($game->isStandoff()) {
            $response['standoff'] = $game->isStandoff();
        } elseif (!empty($game->getWinner())) {
            $response['winner'] = $game->getWinner();
        }

        return new JsonResponse($response, Response::HTTP_OK);
    }
}