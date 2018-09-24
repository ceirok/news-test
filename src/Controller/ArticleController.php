<?php
/**
 * Created by PhpStorm.
 * User: ceirokilp
 * Date: 24/09/2018
 * Time: 10:53
 */

namespace App\Controller;


use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleController extends AbstractController
{
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->user = $tokenStorage->getToken()->getUser();
    }

    /**
     * @Route("/", name="homepage")
     */
    public function home()
    {
        return $this->render('articles/index.html.twig');
    }

    /**
     * @Route("articles/{slug}", name="show_article")
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show($slug)
    {
        return $this->render('articles/show.html.twig');
    }

    /**
     * @Route("/add", name="add_article")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request)
    {
        $article = new Article();
        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, array('label' => 'Article title'))
            ->add('content', TextareaType::class, array('label' => 'Article content'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $article = $form->getData();
            $article->setAuthor($this->getUser());
            $article->setPublishedAt(new \DateTime('now'));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('articles/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}