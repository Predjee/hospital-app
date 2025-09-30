<?php

declare(strict_types=1);

namespace App\Treatment\Infrastructure\EventSubscriber;

use App\Treatment\Domain\Entity\Treatment;
use App\Treatment\Domain\Event\TreatmentCancelledEvent;
use App\Treatment\Domain\Event\TreatmentCompletedEvent;
use App\Treatment\Domain\Event\TreatmentStartedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class TreatmentLifecycleSubscriber implements EventSubscriberInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            TreatmentStartedEvent::class => 'onTreatmentStarted',
            TreatmentCompletedEvent::class => 'onTreatmentCompleted',
            TreatmentCancelledEvent::class => 'onTreatmentCancelled',
        ];
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function onTreatmentStarted(TreatmentStartedEvent $event): void
    {
        /** @var Treatment $treatment */
        $treatment = $this->em->find(Treatment::class, $event->treatmentId);
        $treatment->start();
        $this->em->flush();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function onTreatmentCompleted(TreatmentCompletedEvent $event): void
    {
        /** @var Treatment $treatment */
        $treatment = $this->em->find(Treatment::class, $event->treatmentId);
        $treatment->complete();
        $this->em->flush();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function onTreatmentCancelled(TreatmentCancelledEvent $event): void
    {
        /** @var Treatment $treatment */
        $treatment = $this->em->find(Treatment::class, $event->treatmentId);
        $treatment->cancel();
        $this->em->flush();
    }
}
