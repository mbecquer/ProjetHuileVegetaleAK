<?php

namespace App\Controller;

use App\Entity\Oil;
use App\Entity\Image;
use App\Form\OilType;
use App\Repository\OilRepository;
use App\Repository\FamilyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/oils")
 */
class AdminOilsController extends AbstractController
{
    private $oilRepository;
    private $em;
    public function __construct(OilRepository $oilRepository, EntityManagerInterface $em, FamilyRepository $familyRepository)
    {
        $this->oilRepository = $oilRepository;
        $this->familyRepository = $familyRepository;
        $this->em = $em;
    }
    /**
     * @Route("/", name="admin_oils_index", methods={"GET"})
     */
    public function index(): Response
    {
        $family = $this->familyRepository->findAll();
        $oil = $this->oilRepository->findBy(['family'=>$family]);
        return $this->render('admin_oils/index.html.twig', [
            'oil' => $oil,
            'families'=>$family,
            'title'=>'Admin huiles'
        ]);
    }

    /**
     * @Route("/new", name="admin_oils_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $oil = new Oil();
        $form = $this->createForm(OilType::class, $oil);
        $oil->setCreatedAt(new \DateTimeImmutable());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                # code...
                //on génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                //on copie le fichier dans le dossier upload
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                //on stocke l'image dans la base de données (son nom)
                $img = new Image();
                $img->setName($fichier);
                $oil->addImage($img);
            }
            $entityManager->persist($oil);
            $entityManager->flush();
            $this->addFlash("success", "Huile ajoutée avec succès");
            return $this->redirectToRoute('admin_oils_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_oils/new.html.twig', [
            'oil' => $oil,
            'form' => $form,
            'title'=>'Nouvelle huile'
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
            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                # code...
                //on génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                //on copie le fichier dans le dossier upload
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                //on stocke l'image dans la base de données (son nom)
                $img = new Image();
                $img->setName($fichier);
                $oil->addImage($img);
            }
            $entityManager->flush();
            $this->addFlash("success", "Huile modifiée avec succès");
            return $this->redirectToRoute('admin_oils_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_oils/edit.html.twig', [
            'oil' => $oil,
            'form' => $form,
            'title'=>'Modifier huile'
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
            $this->addFlash("success", "Huile supprimée avec succès");
        }

        return $this->redirectToRoute('admin_oils_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     *  @Route("/{id}", name="oils_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Image $image, Request $request){
        $data = json_decode($request->getContent(), true);
        //on vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
            //on récupère le nom de l'image
            $nom = $image->getName();
            //on supprime le fichier
            unlink($this->getParameter('images_directory') . '/' . $nom);
            //on supprime l'image de la base de données
           $this-> em->remove($image);
           $this-> em->flush();
            $this->addFlash("success", "Image supprimée avec succès");
            //on répond en json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token  invalide'], 400);
        };
    }
}
