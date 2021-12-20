<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'title'=>'Huiles Végétales AK'
        ]);
    }
     /**
     * @Route("/mentions", name="mentions")
     */
    public function mentions(): Response
    {
        return $this->render('home/mentions.html.twig', [
            'controller_name' => 'HomeController',
            'title'=>'Mentions légales'
        ]);
    }
   
    //  /**
    //  * Undocumented function
    //  * @Route("/", name="contact")
    //  */
    // public function contact(Request $request, ContactNotification $notification): Response
    // {
    //     $contact = new Contact();
    //     $form = $this->createForm(ContactType::class, $contact);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $notification->notify($contact);
    //         $this->addFlash('success', 'Email envoyé avec succès');
    //         return $this->redirectToRoute('index');
    //     }
    //     return $this->render("home/index.html.twig", [
    //         "title" => "Huiles Végétales KA",
    //         "image" => "/public/assets/868321CA-CD1B-4D1E-A829-5F07E8325A60.jpeg",
    //         'form' => $form->createView(),
    //     ]);
    // }
}
