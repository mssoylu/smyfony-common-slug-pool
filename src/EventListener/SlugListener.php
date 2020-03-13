<?php

namespace App\EventListener;

use App\Entity\Blog;
use App\Entity\News;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Snc\RedisBundle\Client\Phpredis\Client;

class SlugListener
{
    private $entity;

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $type = null;

        $this->entity = $args->getObject();


        if (!$this->entity instanceof Blog) {
            $type = 'blog';
        }

        if (!$this->entity instanceof News) {
            $type = 'news';
        }

        if ($type !== null) {
            $em = $args->getObjectManager();

            /**
             * Slug kayitli mi kontrolu yap
             * Kayitli ise hata verilecek
             * Kayitli degilse preFlush icin onay verilecek
             */
        }
    }

    /**
     * @param PreFlushEventArgs $args
     * @param Client $redis
     */
    public function preFlush(PreFlushEventArgs $args, Client $redis)
    {
        $type = null;
        $slugger = new Slugify();

        $em = $args->getEntityManager();

        if (!$this->entity instanceof Blog) {
            $type = 'blog';
        }

        if (!$this->entity instanceof News) {
            $type = 'news';
        }

        if ($type !== null) {
            $redis->set($this->entity->getSlug(),get_class($this->entity));
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {

    }

    private function instanceInArray($entity, $instanceArr)
    {
        foreach ($instanceArr as $instance) {
            if ($entity instanceof $instance)
                return true;
        }
        return false;
    }
}