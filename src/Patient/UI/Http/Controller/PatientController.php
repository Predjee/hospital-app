<?php

declare(strict_types=1);

namespace App\Patient\UI\Http\Controller;

use App\Patient\Application\Service\PatientSummaryFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PatientController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(PatientSummaryFactory $factory): Response
    {
        return $this->render('patient/index.html.twig', [
            'patients' => $factory->createCollection(),
        ]);
    }
}
