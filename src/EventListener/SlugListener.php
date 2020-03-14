<?php

namespace App\EventListener;

use App\Entity\Blog;
use App\Entity\News;
use App\Entity\Slug;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;

/**
 * Class SlugListener
 * @package App\EventListener
 */
class SlugListener
{
    private $entity;
    private $redis;
    private $entitiesListArr = ['Blog', 'News'];

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
        $em = $args->getObject();

        foreach ($em->getUnitOfWork()->getScheduledEntityInsertions() as $entity) {
            $arr = explode('\\', get_class($entity));
            $type = end($arr);

            if (in_array($type, $this->entitiesListArr)) {

            }
        }
    }

    /**
     * @param PreFlushEventArgs $args
     * @throws \Doctrine\ORM\ORMException
     */
    public function preFlush(PreFlushEventArgs $args)
    {
        $em = $args->getEntityManager();

        foreach ($em->getUnitOfWork()->getScheduledEntityInsertions() as $entity) {
            $arr = explode('\\', get_class($entity));
            $type = end($arr);

            if (in_array($type, $this->entitiesListArr)) {

                $this->redis->set($entity->getSlug(), $type);

                $slug = new Slug();
                $slug->setSlug($entity->getSlug());
                $slug->setType($type);
                $em->persist($slug);
            }
        }

    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {

    }
}