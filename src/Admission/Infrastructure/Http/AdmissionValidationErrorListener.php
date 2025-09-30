<?php

declare(strict_types=1);

namespace App\Admission\Infrastructure\Http;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[AsEventListener(event: 'kernel.exception', method: 'onKernelException')]
final readonly class AdmissionValidationErrorListener
{
    public function __construct(private Environment $twig)
    {
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof UnprocessableEntityHttpException) {
            return;
        }

        $request = $event->getRequest();

        if ('admit_patient' !== $request->attributes->get('_route')) {
            return;
        }

        $response = new Response(
            $this->twig->render('admission/admit_error.stream.html.twig', [
                'message' => $exception->getMessage(),
            ]),
            Response::HTTP_UNPROCESSABLE_ENTITY,
            ['Content-Type' => 'text/vnd.turbo-stream.html']
        );

        $event->setResponse($response);
    }
}
