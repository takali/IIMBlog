<?php
namespace App\Controller;

use App\Entity\Article;
use App\ETL\Client;
use App\Model\Mailer;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;


use App\Model\Newsletter;


class BlogController extends AbstractController
{

    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
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
}




