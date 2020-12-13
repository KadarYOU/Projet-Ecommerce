<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginFormAuthenticator extends AbstractGuardAuthenticator
{
    protected $encoder;
    public function  __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'security_login'
            &&  $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        // faire sortir les information de Request

        return $request->request->get('login_type_php');
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            return $userProvider->loadUserByUsername($credentials['email']);
        } catch (UsernameNotFoundException $e) {
            throw new AuthenticationException("Cette adresse email n' est pas connue");
        }
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // vérifier que le mot de passe fourni, correspond bien au mot de passe de la base de données.

        $isValid = $this->encoder->isPasswordValid($user, $credentials['password']);

        if (!$isValid) {
            throw new AuthenticationException("Les informations de connexion ne correspondent pas");
        }
        return $isValid;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // todo
        $request->attributes->set(Security::AUTHENTICATION_ERROR, $exception);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        // todo
        return new RedirectResponse("/");
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // redirection au utilisateur admin pour se connecter

        return new RedirectResponse("/login");
    }

    public function supportsRememberMe()
    {
        // todo
    }
}
