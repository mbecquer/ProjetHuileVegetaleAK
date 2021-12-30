<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Entity\ImageArticle;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/articles")
 */
class AdminArticlesController extends AbstractController
{
    private $articleRepository;
    private $em;
    public function __construct(ArticleRepository $articleRepository, EntityManagerInterface $em )
    {
        $this->articleRepository = $articleRepository;
        $this->em = $em;
    }
    /**
     * @Route("/", name="admin_articles_index", methods={"GET"})
     */
    public function index(): Response
    {
        $article = $this->articleRepository->findAll();
        return $this->render('admin_articles/index.html.twig', [
            'articles' => $article,
            'title'=>'Admin articles'
        ]);
    }

    /**
     * @Route("/new", name="admin_articles_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $article->setCreatedAt(new \DateTimeImmutable());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imagesArticle = $form->get('imagesArticle')->getData();
            foreach ($imagesArticle as $image) {
                # code...
                //on génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                //on copie le fichier dans le dossier upload
                $image->move(
                    $this->getParameter('imagesArticle_directory'),
                    $fichier
                );
                //on stocke l'image dans la base de données (son nom)
                $img = new ImageArticle();
                $img->setName($fichier);
                $article->addImageArticle($img);
            }
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash("success", "Article ajouté avec succès");
            return $this->redirectToRoute('admin_articles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_articles/new.html.twig', [
            'article' => $article,
            'form' => $form,
            'title'=>'Nouvel article'
        ]);
    }
    /**
     * @Route("/{id}/edit", name="admin_articles_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagesArticle = $form->get('imagesArticle')->getData();
            foreach ($imagesArticle as $image) {
                # code...
                //on génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                //on copie le fichier dans le dossier upload
                $image->move(
                    $this->getParameter('imagesArticle_directory'),
                    $fichier
                );
                //on stocke l'image dans la base de données (son nom)
                $img = new ImageArticle();
                $img->setName($fichier);
                $article->addImageArticle($img);
            }
            $entityManager->flush();
            $this->addFlash("success", "Article modifié avec succès");
            return $this->redirectToRoute('admin_articles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_articles/edit.html.twig', [
            'article' => $article,
            'form' => $form,
            'title'=>'Modifier article'
        ]);
    }

    /**
     * @Route("/{id}", name="admin_articles_delete", methods={"POST"})
     */
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
            $this->addFlash("success", "Article supprimé avec succès");
        }

        return $this->redirectToRoute('admin_articles_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     *  @Route("/{id}", name="articles_delete_image", methods={"DELETE"})
     */
    public function deleteImageArticle(ImageArticle $imageArticle, Request $request){
        $data = json_decode($request->getContent(), true);
        //on vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete' . $imageArticle->getId(), $data['_token'])) {
            //on récupère le nom de l'image
            $nom = $imageArticle->getName();
            //on supprime le fichier
            unlink($this->getParameter('imagesArticle_directory') . '/' . $nom);
            //on supprime l'image de la base de données
           $this-> em->remove($imageArticle);
           $this-> em->flush();
            $this->addFlash("success", "Image supprimée avec succès");
            //on répond en json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token  invalide'], 400);
        };
    }
}
