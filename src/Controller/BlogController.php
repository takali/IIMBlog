<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/article/{id}", name="front_article", requirements={"id": "\d+"})
     */
    public function article(Request $request, int $id)
    {
        return $this->render('blog/article.html.twig');
    }
}




