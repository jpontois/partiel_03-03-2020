<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\{ArticleRepository};
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ArticleCreate;
use App\Entity\{Article};
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ArticleController extends AbstractController
{
    private $ArticleRepository;
    private $entityManager;

    public function __construct(
        ArticleRepository $ArticleRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->ArticleRepository = $ArticleRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
/*         $article = $this->ArticleRepository->findBy(['published' => true]);
 */
        $article = $this->ArticleRepository->findAll();

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'article' => $article
        ]);
    }

    /**
     * @Route("/gestionArticle", name="gestionArticle")
     * @IsGranted("ROLE_REVIEWER")
     */
    public function validation()
    {
        $currentRole = $this->getUser()->getRoles();

        if( 'ROLE_COMMUNICATION' === $currentRole) {
            $article = $this->ArticleRepository->findBy([
                'published' => false,
            ]);
        } else {
/*             $article = $this->ArticleRepository->findBy([
                'published' => false,
                'author' => !$this->getUser()->getLogin(),
            ]); */
            $article = $this->ArticleRepository->findAll();
        }

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'article' => $article
        ]);
    }

    /**
     * @Route("/article/validate/{id}", name="articleValidate")
     * @ParamConverter("articleID", options={"mapping"={"id"="id"}})
     * @IsGranted("ROLE_COMMUNICATION")
     */
    public function validate(Article $articleID)
    {
        $articleID->setValidated(true);

        $this->entityManager->persist($articleID);
        $this->entityManager->flush();

        $this->addFlash('notice', "Vous avez validé l'article");

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/article/publish/{id}", name="articlePublish")
     * @ParamConverter("articleID", options={"mapping"={"id"="id"}})
     * @IsGranted("ROLE_COMMUNICATION")
     */
    public function publish(Article $articleID)
    {
        $articleID->setPublished(true);

        $this->entityManager->persist($articleID);
        $this->entityManager->flush();

        $this->addFlash('notice', "Vous avez publié l'article");

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/article/create", name="articleCreate")
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request)
    {
        $newArticle = new Article();

        $form = $this->createForm(ArticleCreate::class, $newArticle);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newArticle->setValidated(false);
            $newArticle->setPublished(false);
            $newArticle->setPublicationDate(new \DateTime());
            $newArticle->setAuthor($this->getUser()->getLogin());
            $this->entityManager->persist($newArticle);
            $this->entityManager->flush();
            $this->addFlash('notice', "L'article a bien été ajouté");

            return $this->redirectToRoute('home');
        }

        return $this->render('article/create.html.twig', [
            'createArticleForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/article/edit/{id}", name="articleEdit")
     * @ParamConverter("articleID", options={"mapping"={"id"="id"}})
     * @IsGranted("ROLE_COMMUNICATION")
     */
    public function edit(Request $request, Article $articleID)
    {
        $form = $this->createForm(ArticleCreate::class, $articleID);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($articleID);
            $this->entityManager->flush();
            $this->addFlash('notice', "L'article' a bien été mis à jour");

            return $this->redirectToRoute('home');
        }

        return $this->render('article/edit.html.twig', [
            'editArticleForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/article/delete/{id}", name="articleDelete")
     * @ParamConverter("articleID", options={"mapping"={"id"="id"}})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Article $articleID, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($articleID);
        $entityManager->flush();
        $this->addFlash('notice', "L'article a bien été supprimé");

        return $this->redirectToRoute('home');
    }
}
