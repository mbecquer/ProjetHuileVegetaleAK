<?php

namespace App\Controller;

use App\Entity\Oil;
use App\Form\OilType;
use App\Repository\OilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/oils")
 */
class AdminOilsController extends AbstractController
{
    /**
     * @Route("/", name="admin_oils_index", methods={"GET"})
     */
    public function index(OilRepository $oilRepository): Response
    {
        return $this->render('admin_oils/index.html.twig', [
            'oil' => $oilRepository->findAll(),
            'title'=>'Administration Huiles'
        ]);
    }

    /**
     * @Route("/new", name="admin_oils_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $oil = new Oil();
        $form = $this->createForm(OilType::class, $oil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($oil);
            $entityManager->flush();

            return $this->redirectToRoute('admin_oils_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_oils/new.html.twig', [
            'oil' => $oil,
            'form' => $form,
        ]);
    }

   

    /**
     * @Route("/{id}/edit", name="admin_oils_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Oil $oil, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OilType::class, $oil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_oils_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_oils/edit.html.twig', [
            'oil' => $oil,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_oils_delete", methods={"POST"})
     */
    public function delete(Request $request, Oil $oil, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$oil->getId(), $request->request->get('_token'))) {
            $entityManager->remove($oil);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_oils_index', [], Response::HTTP_SEE_OTHER);
    }
}
