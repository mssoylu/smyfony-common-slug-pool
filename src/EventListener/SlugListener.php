<?php

namespace App\EventListener;

use App\Entity\Slug;
use Doctrine\ORM\Event\PreFlushEventArgs;

/**
 * Class SlugListener
 * @package App\EventListener
 */
class SlugListener
{
    private $redis;
    private $entitiesListArr;

    public function __construct($redis, $entityArr)
    {
        $this->entitiesListArr = $entityArr;
        $this->redis = $redis;
    }

    /**
     * @param PreFlushEventArgs $args
     * @throws \Doctrine\ORM\ORMException
     */
    public function preFlush(PreFlushEventArgs $args)
    {
        $em = $args->getEntityManager();

        foreach ($em->getUnitOfWork()->getScheduledEntityInsertions() as $entity) {
            if (in_array($this->getClassName($entity), $this->entitiesListArr)) {

                $this->redis->set($entity->getSlug(), $this->getClassName($entity));

                $slug = new Slug();
                $slug->setSlug($entity->getSlug());
                $slug->setType($this->getClassName($entity));
                $em->persist($slug);
            }
        }
    }

    /**
     * @param $entity
     * @return string
     */
    private function getClassName($entity)
    {
        $arr = explode('\\', get_class($entity));
        return end($arr);
    }
}