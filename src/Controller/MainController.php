<?php

namespace App\Controller;

use App\Repository\PublicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/', name: 'main_')]
class MainController extends AbstractController
{

    #[Route('', name: 'index')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        if ($this->isGranted("ROLE_USER") && !empty($this->getUser())) {
            return $this->render('main/index.html.twig');
        }else {
            return $this->render('main/login.html.twig',[
                'last_username' => $lastUsername,
                'error' => $error
            ]);
        }
    }

    #[Route('data', name: 'raw-data')]
    public function dataRaw(): Response{
        return $this->render("main/raw-data.twig");
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

}
