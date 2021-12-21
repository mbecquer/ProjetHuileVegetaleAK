<?php

namespace App\Controller;

use App\Entity\Family;
use App\Repository\FamilyRepository;
use App\Repository\OilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OilsController extends AbstractController
{
    /**
     * @Route("/oils", name="oils")
     */
    public function index(OilRepository $oilRepository, FamilyRepository $familyRepository): Response
    {
        $family = $familyRepository->findAll();
        $oil = $oilRepository->findAll();
        return $this->render('home/oils.html.twig', [
            'controller_name' => 'OilsController',
            'title'=>'Nos huiles',
            'oils'=>$oil,
            'families'=>$family
        ]);

    }
     /**
     * @Route("/oils/{id}",name="oil_read")
     */
    public function read( int $id, OilRepository $oilRepository)
    {
        $oil = $oilRepository->find($id);
       

        if ($oil->getId() !== $id) {
            $this->redirectToRoute('oil_read', [
                "id" => $oil->getId(),
              
            ]);
        }
        return $this->render('oil/read.html.twig', [
            'oil' => $oil,

        ]);
    }
    /**
     * @Route("/family/{id}",name="family_oil")
     */
    public function family( int $id, FamilyRepository $familyRepository, OilRepository $oilRepository)
    {
        $family =$familyRepository->find($id);
        $oil = $oilRepository->findAll();
        // $oil = $oilRepository->findBy(['family' => $family]);

        if ($family->getActive() == false) {
            return $this->render('home/index.html.twig', [
                $this->addFlash("success", "Famille non disponible"),
                'title' => 'Huile végétale KA'
            ]);
        }

        if ($family->getId() !== $id) {
            $this->redirectToRoute('family_oil', [
                "id" => $family->getId(),
            
            ]);
        }

        return $this->render('oil/family.html.twig', [
            // 'huiles' => $huile,
            'family' => $family,
            'oils'=>$oil

        ]);
    }
}
