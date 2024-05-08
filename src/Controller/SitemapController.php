<?php

namespace App\Controller;

use App\Entity\ParamPublication;
use App\Repository\ParamPublicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/sitemap', name: 'sitemap_')]
#[IsGranted("ROLE_SEO_TEAM")]
class SitemapController extends AbstractController
{

    #[Route('', name: 'index')]
    public function index(ParamPublicationRepository $paramPublicationRepository): Response
    {
        return $this->render('main/sitemap.html.twig',[
            "sitemap" =>$paramPublicationRepository->findAllActive(),
        ]);
    }

    #[Route('/data', name: 'raw-data')]
    public function dataRaw(): Response{
        return $this->render("main/raw-data.twig");
    }

    #[Route("/add", name: "add", methods: "POST")]
    public function addSitemap(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): RedirectResponse
    {
        $post = $request->request;
        $param = new ParamPublication();
        $param
            ->setCreatedAt(new \DateTimeImmutable())
            ->setCreatedBy($this->getUser())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setUpdatedBy($this->getUser())
            ->setType($post->get("type") === "xml" ? "xml-sitemap" : ($post->get("type") === "rss" ? "rss-feed": "xml-sitemap"))
            ->setLocal($post->get("location"))
            ->setUrl($post->get("url"));
        $errors = $validator->validate($param);

        if(empty($errors->count())){
            $this->addFlash("success", "Hai inserito una nuova sitemap alla coda dello scraping");
            $entityManager->persist($param);
            $entityManager->flush();
        }else{
            $this->addFlash("danger", "la url non è valida");
        }

        return new RedirectResponse('/sitemap');
    }

    #[Route("/{siteMap}", name: "delete", methods: "GET")]
    public function deleteSitemap(ParamPublication $siteMap, EntityManagerInterface $entityManager):RedirectResponse
    {
        $siteMap
            ->setDeletedAt(new \DateTimeImmutable())
            ->setDeletedBy($this->getUser());

        $entityManager->persist($siteMap);
        $entityManager->flush();

        return new RedirectResponse('/sitemap');
    }

}
