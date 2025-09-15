<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PatientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PatientController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PatientRepository $repo): Response
    {
        $summaries = $repo->findSummaries();

        return $this->render('patient/index.html.twig', [
            'patients' => $summaries,
        ]);
    }
}
