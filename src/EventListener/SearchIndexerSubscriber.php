<?php
namespace App\EventListener;

use App\Entity\Article;
use App\ETL\Client;
use App\ETL\Transform;
use App\Model\ETLArticle;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class SearchIndexerSubscriber implements EventSubscriber
{
    /**
     * @var ETLArticle
     */
    protected $etl_article;

    /**
     * @var string
     */
    protected $type = 'article';


    public function __construct(ETLArticle $etl_article)
    {
        $this->etl_article = $etl_article;
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
            $this->etl_article->indexOne($entity);
        }
    }
}
