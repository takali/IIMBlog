<?php
namespace App\Controller;

use App\Entity\Article;
use App\ETL\Client;
use App\Model\Mailer;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;


use App\Model\Newsletter;

class BlogController extends AbstractController
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(Client $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/article/{id}", name="front_article", requirements={"id": "\d+"})
     */
    public function article(Request $request, $id)
    {
        // your code...

        $params = [
            'index' => $this->client->getIndex(),
            'type' => 'doc',
            'body' => [
                'query' => [
                    'match' => [
                        'name' => 'toto'
                    ]
                ]
            ]
        ];

        $result = $this->client->search($params);


        return $this->render('blog/article.html.twig');
    }

    /**
     * @Route("/article/add", name="front_article", requirements={"id": "\d+"})
     */
    public function articleCreate()
    {
        $article = new Article();
        $article->setTitle('toto');
        $article->setContent('toto');

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return new Response('success');
    }
}
