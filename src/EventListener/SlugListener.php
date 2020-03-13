<?php

namespace App\EventListener;

use App\Entity\Blog;
use App\Entity\News;
use App\Entity\Slug;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Snc\RedisBundle\Client\Phpredis\Client;

class SlugListener
{
    private $entity;

    private $redis;

    public function __construct($redis)
    {
        $this->redis = $redis;
    }

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
     * @throws \Doctrine\ORM\ORMException
     */
    public function preFlush(PreFlushEventArgs $args)
    {
        $type = null;
        $slugger = new Slugify();

        $em = $args->getEntityManager();

        foreach ($em->getUnitOfWork()->getScheduledEntityUpdates() as $entity) {
            if (!$this->entity instanceof Blog) {
                $type = 'blog';
            }

            if (!$this->entity instanceof News) {
                $type = 'news';
            }

            if ($type !== null) {

                $this->redis->set($this->entity->getSlug(), get_class($this->entity));

                $slug = new Slug();
                $slug->setSlug($this->entity->getTitle());
                $slug->setType(get_class($this->entity));
                $em->persist($slug);
            }
        }

        foreach ($em->getUnitOfWork()->getScheduledEntityInsertions() as $entity) {
            if (!$this->entity instanceof Blog) {
                $type = 'blog';
            }

            if (!$this->entity instanceof News) {
                $type = 'news';
            }

            if ($type !== null) {

                $this->redis->set($this->entity->getSlug(), get_class($this->entity));

                $slug = new Slug();
                $slug->setSlug($this->entity->getTitle());
                $slug->setType(get_class($this->entity));
                $em->persist($slug);
            }
        }

    }

    public
    function preUpdate(LifecycleEventArgs $args)
    {

    }

    private
    function instanceInArray($entity, $instanceArr)
    {
        foreach ($instanceArr as $instance) {
            if ($entity instanceof $instance)
                return true;
        }
        return false;
    }
}