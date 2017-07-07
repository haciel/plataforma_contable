<?php


namespace BackendBundle\Listeners;


use BackendBundle\Useful\IPosition;
use BackendBundle\Useful\UploadFile;
use Doctrine\ORM\Event\LifecycleEventArgs;

class UpdatePositionListener {

    public function prePersist(LifecycleEventArgs $args) {
        $entity=$args->getEntity();
        if($entity instanceof IPosition)
        {
            $name=(new \ReflectionClass($entity))->getShortName();
            $position=count($args->getEntityManager()->getRepository('BackendBundle:'.$name)->findAll())+1;
            $entity->setPosition($position);
        }
    }

}