<?php
namespace App\EventListener;

use App\Entity\Article;
use App\ETL\Client;
use App\ETL\Transform;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class SearchIndexerSubscriber implements EventSubscriber
{
    /**
     * @var Transform
     */
    protected $transform;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $type = 'article';


    public function __construct(Transform $transform, Client $client)
    {
        $this->transform = $transform;
        $this->client = $client;
    }

    public function getSubscribedEvents()
    {
        return [
            'postPersist',
            'postUpdate',
        ];
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function index(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Article) {
            $articleTransformed = $this->transform->transformArticle($entity);

            $this->client->index($articleTransformed, $this->type);
        }
    }
}
