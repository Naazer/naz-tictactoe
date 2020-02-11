<?php

namespace App\Controller;

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
     * @Route("/action", name="api_action", methods="POST", defaults={"_format": "json"})
     *
     * @param Request
     * @return JsonResponse
     */
    public function action(): JsonResponse
    {
        return new JsonResponse([], Response::HTTP_OK);
    }
}