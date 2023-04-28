<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\MissingTokenException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * JWT Created Subscriber
 * 
 * Enhance token payload with user data
 * 
 *  // If user has been blocked, an Exception is thrown
 */
class JWTCreatedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private RequestStack $requestStack,
    ) {
    }


    /**
     * On JWT Created
     *
     * @param JWTCreatedEvent $event
     * @throws MissingTokenException if user is blocked
     * @return void
     */
    public function onLexikJwtAuthenticationOnJwtCreated(JWTCreatedEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();

        // If user can be blocked blocked
        // if ($user->isBlocked()) {
        //     throw new MissingTokenException('User Blocked.', 401);
        // }

        $request = $this->requestStack->getCurrentRequest();
        $payload = $event->getData();

        $payload['id'] = $user->getId();
        $payload['username'] = $user->getUsername();
        $payload['requestIp'] = $request->getClientIp();

        $event->setData($payload);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_jwt_authentication.on_jwt_created' => 'onLexikJwtAuthenticationOnJwtCreated',
        ];
    }
}
