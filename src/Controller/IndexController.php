<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class IndexController
 * @package App\Controller
 *
 * @author Nazar Salo <salo.nazar@gmail.com>
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index", methods="GET")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }
}