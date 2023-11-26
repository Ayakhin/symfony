<?php

namespace App\Security;

use App\Repository\MembreRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class SecurityAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'connexion';

    private $membreRepository ; 

    public function __construct(private UrlGeneratorInterface $urlGenerator , MembreRepository $membreRepository)
    {
        $this->membreRepository = $membreRepository;
    }

    public function authenticate(Request $request ): Passport
    {
        $email_ou_pseudo = $request->request->get('email', '');

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email_ou_pseudo);

        return new Passport(
            new UserBadge($email_ou_pseudo , function($login){
                return $this->membreRepository->findByEmailOrUsername($login);
            }),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),            ]
        );
        // passport => clé qui permet d'autilisation de naviger dans la partie sécurisée du site 
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        /**
         * @var App\Entity\Membre ;
         */
        $membre = $token->getUser();

        // For example:
        if($membre->isAdmin()){

            return new RedirectResponse($this->urlGenerator->generate('article_list'));
        }
        return  new RedirectResponse($this->urlGenerator->generate('app_profile'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
