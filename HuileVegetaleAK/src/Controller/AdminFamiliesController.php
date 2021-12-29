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
            $this->addFlash("success", "Famille ajoutée avec succès");
            return $this->redirectToRoute('admin_oils_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_families/new.html.twig', [
            'family' => $family,
            'form' => $form,
            'title'=>'Nouvelle famille'
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
            $this->addFlash("success", "Famille modifiée avec succès");
            return $this->redirectToRoute('admin_oils_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_families/edit.html.twig', [
            'family' => $family,
            'form' => $form,
            'title'=>'Modifier famille'
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
            $this->addFlash("success", "Famille supprimée avec succès");
        }

        return $this->redirectToRoute('admin_oils_index', [], Response::HTTP_SEE_OTHER);
    }
}
