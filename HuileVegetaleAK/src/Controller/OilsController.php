<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OilsController extends AbstractController
{
    /**
     * @Route("/oils", name="oils")
     */
    public function index(): Response
    {
        return $this->render('home/oils.html.twig', [
            'controller_name' => 'OilsController',
            'title'=>'Nos huiles'
        ]);
    }
}
