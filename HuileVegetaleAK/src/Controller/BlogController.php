<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController{

      /**
     * @Route("/blog", name="blog")
     */
    public function blog(ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'title'=>'Blog',
            'articles'=>$article
        ]);
    }
        /**
     * @Route("/blog/{id}",name="blog_read")
     */
    public function read(int $id, Request $request, EntityManagerInterface $em, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);
        // $commentaires = $commentRepository->findBy([
        //     'article' => $article
        // ]);
        //partie commentaires
        //on crée le commentaire vierge
        // $comment = new Comment();
        //on génère le formulaire
        // $form = $this->createForm(CommentType::class, $comment);
        // $form->handleRequest($request);
        //traitement formulaire
        // if ($form->isSubmitted() && $form->isValid()) {
        //     $comment->setCreatedAt(new \DateTime());
        //     $comment->setArticle($article);
        //     //on recupere le contenu du champ parent
        //     $parentid = $form->get('parent')->getData();
        //     //on va chercher le commentaire correspondant 
        //     if ($parentid != null) {
        //         $parent = $em->getRepository(Comment::class)->find($parentid);
        //     }

        //     //on définit le parent
        //     $comment->setParent($parent ?? null);

        //     $em->persist($comment);
        //     $em->flush();

        //     $this->addFlash('message', 'Votre commentaire a bien été envoyé');
        //     return $this->redirectToRoute('blog_read', ['id' => $article->getId()]);
        // }

        return $this->render('blog/read.html.twig', [
            'article' => $article,
            'title'=>$article->getTitle()
            // 'form' => $form->createView(),
            // 'commentaires' => $commentaires,
        ]);
    }
}