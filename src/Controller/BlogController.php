<?php
namespace App\Controller;

use App\Entity\Article;
use App\ETL\Client;
use App\Model\Mailer;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/article/search/{search}", name="front_article_search")
     */
    public function article($search)
    {

        //https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-multi-match-query.html

        $params = [
            'index' => $this->client->getIndex(),
            'type' => 'doc',
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' =>    $search,
                        'fields' => [
                            'title^3',
                            'content'
                        ],
                        'minimum_should_match' => '50%',
                        'type' => 'most_fields',
                        'fuzziness' => 'AUTO',
                        //'operator' => 'and', //look at cross_fields type before use operator "and"
                    ]
                ],
            ]
        ];


        $params = [
            'index' => $this->client->getIndex(),
            'type' => 'doc',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            'multi_match' => [
                                'query' =>    $search,
                                'fields' => [
                                    'title^3',
                                    'content'
                                ],
                                'minimum_should_match' => '50%',
                                'type' => 'most_fields',
                                'fuzziness' => 'AUTO',
                                //'operator' => 'and', //look at cross_fields type before use operator "and"
                            ]
                        ],

                        // complete geo-location distance here with :
                        // https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geo-distance-query.html#_lat_lon_as_properties_3
                    ]
                ]
            ]
        ];


        $result = $this->client->search($params);

        return new JsonResponse($result);
    }

    /**
     * @Route("/article/add", name="front_article", requirements={"id": "\d+"})
     */
    public function articleCreate()
    {
        $article = new Article();
        $article->setTitle('toto');
        $article->setContent('toto');
        $article->setLatitude(47);
        $article->setLongitude(47);

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return new Response('success');
    }
}
