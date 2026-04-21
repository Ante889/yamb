<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Doctrine\Subscriber;

use DateTime;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;


#[AsDoctrineListener(event: Events::prePersist)]
#[AsDoctrineListener(event: Events::preUpdate)]
final readonly class DoctrineLifecycleEventSubscriber
{
    public function __construct()
    {
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        if ($this->isCommandLineInterface()) return;

        $entity = $args->getObject();

        if (
            method_exists($entity, 'setCreated')
        ) {
            $entity->setCreated(new DateTime());
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        if ($this->isCommandLineInterface()) return;

        $entity = $args->getObject();

        if (
            method_exists($entity, 'setChanged')
        ) {
            $entity->setChanged(new DateTime());
        }
    }

    private function isCommandLineInterface(): bool
    {
        return php_sapi_name() === 'cli';
    }
}
