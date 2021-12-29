<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/articles")
 */
class AdminArticlesController extends AbstractController
{
    /**
     * @Route("/", name="admin_articles_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('admin_articles/index.html.twig', [
            'articles' => $articleRepository->findAll(),
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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $this->addFlash("success", "Article ajouté avec succès");
            $entityManager->flush();

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
}
