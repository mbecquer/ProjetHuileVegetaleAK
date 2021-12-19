<?php

namespace App\Controller;

use App\Entity\Family;
use App\Form\FamilyType;
use App\Repository\FamilyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/families")
 */
class AdminFamiliesController extends AbstractController
{
    /**
     * @Route("/", name="admin_families_index", methods={"GET"})
     */
    public function index(FamilyRepository $familyRepository): Response
    {
        return $this->render('admin_families/index.html.twig', [
            'families' => $familyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_families_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $family = new Family();
        $form = $this->createForm(FamilyType::class, $family);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($family);
            $entityManager->flush();

            return $this->redirectToRoute('admin_families_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_families/new.html.twig', [
            'family' => $family,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_families_show", methods={"GET"})
     */
    public function show(Family $family): Response
    {
        return $this->render('admin_families/show.html.twig', [
            'family' => $family,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_families_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Family $family, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FamilyType::class, $family);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_families_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_families/edit.html.twig', [
            'family' => $family,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_families_delete", methods={"POST"})
     */
    public function delete(Request $request, Family $family, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$family->getId(), $request->request->get('_token'))) {
            $entityManager->remove($family);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_families_index', [], Response::HTTP_SEE_OTHER);
    }
}
