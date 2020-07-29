<?php
declare(strict_types=1);


namespace App\EventListener;


use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class FormSubmitExceptionListener
{
    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        if ($event->getRequest()->headers->get('accepts') !== 'application/json') {
            return;
        }

        if ($event->getThrowable() instanceof InvalidArgumentException) {
            $response = new JsonResponse(['message' => $event->getThrowable()->getMessage()], 500);

            $event->setResponse($response);
            return;
        }

        //keep standard symfony output on unknown error
    }
}