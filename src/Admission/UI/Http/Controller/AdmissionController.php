<?php

declare(strict_types=1);

namespace App\Admission\UI\Http\Controller;

use App\Admission\UI\Input\PatientAdmissionInput;
use App\Patient\Application\Command\AdmitPatientCommand;
use App\Patient\Application\Query\PatientFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AdmissionController extends AbstractController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route('/admit', name: 'admit_patient', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload] PatientAdmissionInput $intake,
        MessageBusInterface $commandBus,
        PatientFinder $finder,
    ): Response {
        $commandBus->dispatch(
            new AdmitPatientCommand($intake->name, $intake->birthDate)
        );

        return $this->render('admission/admit.stream.html.twig', [
            'patients' => $finder->all(),
            'message' => "New patient '{$intake->name}' admitted.",
        ], new Response(
            headers: ['Content-Type' => 'text/vnd.turbo-stream.html']
        ));
    }
}
