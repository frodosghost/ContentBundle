<?php

namespace Manhattan\Bundle\ContentBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;

use Manhattan\Bundle\ContentBundle\Entity\Asset;

/**
 * Lifecycle listeners for persisting Assets to database.
 *
 * @author James Rickard <james@frodosghost.com>
 */

class ObjectPersistListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // If it is a photo then we run the upload() function
        if ($entity instanceof Asset) {
            $entity->preUpload();
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // If it is a photo then we run the upload() function
        if ($entity instanceof Asset) {
            $entity->upload();
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // If it is a photo then we run the preUpdate() function
        if ($entity instanceof Asset) {
            $entity->preUpdateAsset();
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // If it is a photo then we run the upload() function
        if ($entity instanceof Asset) {
            $entity->replace();
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // If it is a photo then we run the upload() function
        if ($entity instanceof Asset) {
            $entity->storeFilenameForRemove();
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // If it is a photo then we run the upload() function
        if ($entity instanceof Asset) {
            $entity->removeUpload();
        }
    }

}
