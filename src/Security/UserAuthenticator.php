<?php

namespace App\Security;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    protected const LOGIN_ROUTE = 'main_index';

    private UrlGeneratorInterface $urlGenerator;
    private EntityManagerInterface $entityManager;
    private  UserRepository $userRepository;

    public function __construct(UrlGeneratorInterface $urlGenerator, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->urlGenerator = $urlGenerator;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }
    public function supports(Request $request): bool
    {
        return ($request->getPathInfo() === '/' && $request->isMethod('POST') && !$request->getUser());
    }


    public function authenticate(Request $request): Passport
    {

        $email = $request->request->get('email', '');

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user || !$user->isEnabled()) {
            throw new DisabledException('L\'utente non è abilitato o non esiste.');
        }

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $login = $token->getUser();
        $login->setLastLogin(new \DateTimeImmutable());
        $this->entityManager->persist($login);
        $this->entityManager->flush();

        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('main_index'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
