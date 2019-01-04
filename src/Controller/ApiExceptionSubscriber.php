<?php
/**
 * Created by PhpStorm.
 * User: rmartine
 * Date: 02/01/2019
 * Time: 20:54
 */

namespace App\Controller;

use App\Controller\JsonResponseBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        /**
         * Eventos del kernel symfony al que subscribirse
         */
        return array(
            KernelEvents::EXCEPTION => 'onKernelException'
        );
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $e = $event->getException();
        $response = JsonResponseBuilder::fromException($e)->build();
        $event->setResponse($response);
    }

}