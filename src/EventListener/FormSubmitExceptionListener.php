<?php
declare(strict_types=1);


namespace App\EventListener;


use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class FormSubmitExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        if (! $event->getThrowable() instanceof InvalidArgumentException) {
            return;
        }

        $response = new JsonResponse(['message' => $event->getThrowable()->getMessage()], 500);

        $event->setResponse($response);
    }
}