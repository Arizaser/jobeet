<?php 
namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Entity\Job;

class JobTokenListener
{
    /**
    * @param LifecycleEventArgs $args
    */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
       if (!$entity instanceof Job) {
        return;
       }
    if (!$entity->getToken()) {
        $entity->setToken(\bin2hex(\random_bytes(10)));
       }
    }
}
