<?php

namespace App\ApiBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class IpAddressSubscriber implements EventSubscriberInterface
{
    private $ipWhitelist;
    private $methodsToCheck;

    /**
     * @param array $ipWhitelist
     */
    public function __construct(array $ipWhitelist)
    {
        $this->ipWhitelist = $ipWhitelist;
        $this->methodsToCheck = [
            '/api/create',
            '/api/read',
            '/api/update',
            '/api/delete'
        ];
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [RequestEvent::class => 'onKernelRequest'];
    }

    /**
     * @param RequestEvent $event
     * @return void
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        if (in_array($event->getRequest()->getPathInfo(), $this->methodsToCheck)
            && !in_array($event->getRequest()->getClientIp(), $this->ipWhitelist)
        ) {
            throw new AccessDeniedHttpException('Access Denied');
        }
    }
}