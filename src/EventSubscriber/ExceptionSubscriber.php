<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    // Create simple subscriber to listen for exceptions and give at least a message and static error code while in production
    public function onKernelException(ExceptionEvent $event): void
    {

        $exception = $event->getThrowable();
        $error = [
            'message' => $exception->getMessage(),
            'error' => '404'
        ];
        $response = new JsonResponse($error);

        $event->setResponse($response);
    }
}



