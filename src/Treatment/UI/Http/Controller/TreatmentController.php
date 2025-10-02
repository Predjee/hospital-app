<?php

declare(strict_types=1);

namespace App\Treatment\UI\Http\Controller;

use App\Treatment\Application\Command\PerformTreatmentCommand;
use App\Treatment\Application\Result\TreatmentResult;
use App\Treatment\UI\Input\TreatmentActionInput;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Ulid;

final class TreatmentController extends AbstractController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route('/treat', name: 'treat_patient', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload] TreatmentActionInput $dto,
        MessageBusInterface $commandBus,
    ): Response {
        $command = new PerformTreatmentCommand(Ulid::fromString($dto->treatmentId));
        $envelope = $commandBus->dispatch($command);

        /** @var TreatmentResult $result */
        $result = $envelope->last(HandledStamp::class)?->getResult();

        return $this->render('treatment/treat.stream.html.twig', [
            'patient' => $result->patientSummary,
            'message' => $result->message,
        ], new Response(
            headers: ['Content-Type' => 'text/vnd.turbo-stream.html']
        ));
    }
}
