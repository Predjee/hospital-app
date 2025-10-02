<?php

declare(strict_types=1);

namespace App\Treatment\Infrastructure\Http;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Twig\Environment;

#[AsEventListener(event: 'kernel.exception', method: 'onKernelException')]
final readonly class TreatmentValidationErrorListener
{
    public function __construct(private Environment $twig)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof UnprocessableEntityHttpException) {
            return;
        }

        $request = $event->getRequest();

        if ('treat_patient' !== $request->attributes->get('_route')) {
            return;
        }

        $response = new Response(
            $this->twig->render('treatment/treat_error.stream.html.twig', [
                'message' => $exception->getMessage(),
            ]),
            Response::HTTP_UNPROCESSABLE_ENTITY,
            ['Content-Type' => 'text/vnd.turbo-stream.html']
        );

        $event->setResponse($response);
    }
}
