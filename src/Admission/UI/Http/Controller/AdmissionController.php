<?php

declare(strict_types=1);

namespace App\Admission\UI\Http\Controller;

use App\Admission\UI\Input\PatientAdmissionInput;
use App\Patient\Application\Service\PatientService;
use App\Patient\Application\Service\PatientSummaryFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class AdmissionController extends AbstractController
{
    #[Route('/admit', name: 'admit_patient', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload] PatientAdmissionInput $intake,
        PatientService $patientService,
        PatientSummaryFactory $factory,
    ): Response {
        $patient = $patientService->admit($intake);

        return $this->render('admission/admit.stream.html.twig', [
            'patients' => $factory->createCollection(),
            'message' => "Nieuwe patiënt '{$patient->name()}' toegevoegd.",
        ], new Response(
            headers: ['Content-Type' => 'text/vnd.turbo-stream.html']
        ));
    }
}
