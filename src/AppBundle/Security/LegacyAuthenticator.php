<?php

namespace AppBundle\Security;

use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

class LegacyAuthenticator implements SimplePreAuthenticatorInterface
{
    protected $sessoinStorage;

    protected static $rolesMap = [
        'old_role_admin' => 'ROLE_ADMIN',
        'old_role_user' => 'ROLE_USER',
        'old_role_super_admin' => 'ROLE_SUPER_ADMIN',
    ];

    public function __construct(NativeSessionStorage $sessionStorage)
    {
        $this->sessoinStorage = $sessionStorage;
    }

    public function createToken(Request $request, $providerKey)
    {
        if ($this->sessoinStorage->isStarted()) {
            $this->sessoinStorage->start();
        }

        if (isset($_SESSION['userId']) && isset($_SESSION['username'])) {
            $userName = $_SESSION['username'];
            $roles = (array) $_SESSION['roles'];
            $roles = array_map(function ($element) {
                    if (!isset(self::$rolesMap[$element])) {
                        throw new \RuntimeException(sprintf('Legacy role "%s" cannot be mapped to new system Role', $element));
                    }

                    return (string) self::$rolesMap[$element];
                },
                $roles
            );

            return new PreAuthenticatedToken(
                $userName,
                $request->getSession()->getId(),
                $providerKey,
                $roles
            );
        }
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof EntityUserProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of EntityUserProvider (%s was given).',
                    get_class($userProvider)
                )
            );
        }

        $userName = $token->getUser();
        $user = $userProvider->loadUserByUsername($userName);

        if (!$user) {
            throw new CustomUserMessageAuthenticationException(
                sprintf('API Key "%s" does not exist.', $userName)
            );
        }

        return new PreAuthenticatedToken(
            $user,
            $userName,
            $providerKey,
            $token->getRoles()
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }
}