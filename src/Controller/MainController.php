<?php

namespace App\Controller;


use App\Repository\ParamPublicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


#[Route('', name: 'main_')]
class MainController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET','POST'])]
    public function index(AuthenticationUtils $authenticationUtils, ParamPublicationRepository $paramPublicationRepository): Response
    {

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        if ($this->isGranted("ROLE_GUEST") && !empty($this->getUser())) {

            return $this->render('main/index.html.twig',[
                "sitemap" =>$paramPublicationRepository->findAllActive(),
            ]);

        }else {
            return $this->render('main/login.html.twig',[
                'last_username' => $lastUsername,
                'error' => $error
            ]);
        }
    }
    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

}
