<?php

declare(strict_types=1);

namespace App\Treatment\UI\Http\Controller;

use App\Patient\Application\Service\PatientSummaryFactory;
use App\Treatment\Application\Strategy\TreatmentResolver;
use App\Treatment\Domain\Event\TreatmentCompletedEvent;
use App\Treatment\Domain\Event\TreatmentStartedEvent;
use App\Treatment\Infrastructure\Repository\TreatmentRepository;
use App\Treatment\UI\Input\TreatmentActionInput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class TreatmentController extends AbstractController
{
    #[Route('/treat', name: 'treat_patient', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload] TreatmentActionInput $dto,
        TreatmentRepository $treatments,
        TreatmentResolver $resolver,
        EventDispatcherInterface $dispatcher,
        PatientSummaryFactory $patientSummaryFactory,
    ): Response {
        try {
            $treatment = $treatments->find($dto->treatmentId);
            if (!$treatment) {
                throw new \RuntimeException("Behandeling #{$dto->treatmentId} niet gevonden!");
            }

            $dispatcher->dispatch(new TreatmentStartedEvent($treatment->id(), $treatment->patient()->id(), new \DateTimeImmutable()));

            $message = $resolver->resolve($treatment->patient(), $treatment->type());

            $dispatcher->dispatch(new TreatmentCompletedEvent($treatment->id(), $treatment->patient()->id(), new \DateTimeImmutable()));

            $type = 'success';

            $patientSummary = $patientSummaryFactory->createFromEntity($treatment->patient());
        } catch (\Throwable $e) {
            $message = 'Fout bij behandeling: '.$e->getMessage();
            $type = 'error';

            return new Response(
                $message,
                Response::HTTP_UNPROCESSABLE_ENTITY,
                ['Content-Type' => 'text/vnd.turbo-stream.html']
            );
        }

        return $this->render('treatment/treat.stream.html.twig', [
            'patient' => $patientSummary,
            'treatment' => $treatment,
            'message' => $message,
            'type' => $type,
        ], new Response(
            headers: ['Content-Type' => 'text/vnd.turbo-stream.html']
        ));
    }
}
