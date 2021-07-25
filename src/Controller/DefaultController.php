<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig');
    }
}
