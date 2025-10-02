<?php

declare(strict_types=1);

namespace App\Audit\UI\Http\Controller;

use App\Audit\Application\Query\AuditLogFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuditController extends AbstractController
{
    #[Route('/audit', name: 'audit_overview', methods: ['GET'])]
    public function __invoke(AuditLogFinder $finder): Response
    {
        return $this->render('audit/index.html.twig', [
            'logs' => $finder->recent(),
        ]);
    }
}
