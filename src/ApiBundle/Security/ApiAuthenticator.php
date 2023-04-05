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

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return $request->headers->has('Sky-authorization') || !$request->headers->has('Sky-authorization');
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function getCredentials(Request $request)
    {
        return $request->headers->get('Sky-authorization') === 'main300';
    }

    /**
     * @param $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface|null
     */
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

    /**
     * @param $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    /**
     * @param Request $request
     * @param $token
     * @param string $providerKey
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, $token, string $providerKey): ?Response
    {
        return null;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return Response
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        $data = [
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return bool
     */
    public function supportsRememberMe(): bool
    {
        return false;
    }
}