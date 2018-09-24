<?php
/**
 * Created by PhpStorm.
 * User: ceirokilp
 * Date: 24/09/2018
 * Time: 10:53
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function home()
    {
        return $this->render('articles/index.html.twig');
    }
}