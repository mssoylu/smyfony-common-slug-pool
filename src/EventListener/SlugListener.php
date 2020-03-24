<?php

namespace App\EventListener;

use App\Entity\Slug;
use Doctrine\ORM\Event\LifecycleEventArgs;

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

    public function postUpdate(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $entity = $args->getEntity();

        $slug = $em->getRepository(Slug::class)->findOneBy([
            'itemId' => $entity->getId(),
            'type' => $this->getClassName($entity)
        ]);

        if ($slug) {
            $this->redis->set($entity->getSlug(), json_encode([$this->getClassName($entity), $entity->getId()]));

            $slug->setSlug($entity->getSlug());
            $slug->setType($this->getClassName($entity));
            $slug->setItemId($entity->getId());
            $em->persist($slug);
            $em->flush();
        }
    }

    /**
     * Yeni bir entity eklenirse
     *
     * @param LifecycleEventArgs $args
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $entity = $args->getEntity();

        if (in_array($this->getClassName($entity), $this->entitiesListArr)) {
            $this->redis->set($entity->getSlug(), json_encode([$this->getClassName($entity), $entity->getId()]));
            $slug = new Slug();
            $slug->setSlug($entity->getSlug());
            $slug->setType($this->getClassName($entity));
            $slug->setItemId($entity->getId());
            $em->persist($slug);
            $em->flush();
        }
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        $this->redis->delete($entity->getSlug());

        $slug = $em->getRepository(Slug::class)->findOneBy([
            'type' => $this->getClassName($entity),
            'itemId' => $entity->getId()
        ]);

        if ($slug) {
            $em->remove($slug);
            $em->flush();
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