<?php
namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Entity\Article;

class ArticleListener
{
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // only act on some "Product" entity
        if (!$entity instanceof Article) {
            return;
        }

        $entity->setUpdatedAt(new \DateTime());
    }
}















