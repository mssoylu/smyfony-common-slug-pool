<?php

namespace App\EventListener;

use App\Entity\Blog;
use App\Entity\News;
use App\Entity\Slug;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class SlugListener
{
    private $entity;

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        /**
         * Slug kayitli mi kontrolu
         */
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

        if (!$this->entity instanceof Blog) {
            $type = 'blog';
        }

        if (!$this->entity instanceof News) {
            $type = 'news';
        }

        if ($type !== null) {
            $slug = new Slug();
            $slug->setSlug($slugger->slugify($this->entity->getTitle()));
            $slug->setType($type);
            $em->persist($slug);
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        /**
         * Slug kayitli mi kontrolu
         */
        echo 'upodate'; exit;
    }
}