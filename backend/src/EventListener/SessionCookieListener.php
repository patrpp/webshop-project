<?php
namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class SessionCookieListener
{
    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $request = $event->getRequest();

        // csak ha van session cookie
        foreach ($response->headers->getCookies() as $cookie) {
            if ($cookie->getName() === session_name()) {
                // új cookie ugyanazokkal az értékekkel, de samesite None
                $newCookie = cookie_create(
                    $cookie->getName(),
                    $cookie->getValue(),
                    [
                        'expires' => $cookie->getExpiresTime(),
                        'path' => $cookie->getPath(),
                        'domain' => $cookie->getDomain(),
                        'secure' => $cookie->isSecure(),
                        'httponly' => $cookie->isHttpOnly(),
                        'samesite' => 'None'
                    ]
                );
                $response->headers->setCookie($newCookie);
            }
        }
    }
}
