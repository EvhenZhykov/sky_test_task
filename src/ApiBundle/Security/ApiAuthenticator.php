<?php

namespace App\ApiBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\Response;


class ApiAuthenticator extends AbstractGuardAuthenticator
{

    public function supports(Request $request): bool
    {
        return $request->headers->has('Sky-authorization') || !$request->headers->has('Sky-authorization');
    }

    public function getCredentials(Request $request)
    {
        return $request->headers->get('Sky-authorization') === 'main300';
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        if (false === $credentials) {
            return null;
        }

        // If this returns a user, checkCredentials() is called next:
        return new class implements UserInterface {
            public function getUsername() {}
            public function getPassword() {}
            public function getRoles() {
                return ['ROLE_USER'];
            }
            public function getSalt() {}
            public function eraseCredentials() {}
        };

    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, $token, string $providerKey): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        $data = [
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}