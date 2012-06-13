<?php
namespace Manhattan\Bundle\ContentBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Manhattan\Bundle\ContentBundle\Entity\Asset;

class ObjectPersistListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // If it is a photo then we run the upload() function
        if ($entity instanceof Asset) {
            $entity->preUpload();
        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // If it is a photo then we run the upload() function
        if ($entity instanceof Asset) {
            $entity->storeFilenameForRemove();
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // If it is a photo then we run the upload() function
        if ($entity instanceof Asset) {
            $entity->upload();
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // If it is a photo then we run the upload() function
        if ($entity instanceof Asset) {
            $entity->upload();
        }
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // If it is a photo then we run the upload() function
        if ($entity instanceof Asset) {
            $entity->removeUpload();
        }
    }

}
