<?php

declare(strict_types=1);

namespace App\Patient\UI\Http\Controller;

use App\Patient\Application\Query\PatientFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PatientController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(PatientFinder $finder): Response
    {
        return $this->render('patient/index.html.twig', [
            'patients' => $finder->all(),
        ]);
    }
}
