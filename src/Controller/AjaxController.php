<?php

namespace App\Controller;

use App\Entity\ParamPublication;
use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\ExpressionBuilder;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\InvalidArgumentException;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

#[Route('/ajax/', name: 'ajax_'/*, condition: "request.isXmlHttpRequest()"*/)]
class AjaxController extends AbstractController
{

    #[Route('get-data', name: 'data', methods: "GET")]
    public function publicationData(PublicationRepository $publicationRepository, Request $request): JsonResponse
    {

        $start = $request->query->get("start");
        $limit = $request->query->get("length");
        $draw = $request->query->get("draw");
        $data = $publicationRepository
            ->createQueryBuilder("data")
            ->setFirstResult($start)
            ->setMaxResults($limit)
	    ->orderBy("data.datetime", "DESC")
            ->getQuery()
            ->getArrayResult();

        return $this->json(
            [
                "post" => [$start, $limit, $draw],
                "data" => $data
            ]);
    }

    #[Route("sitemap/{siteMap}", name: "edit-sitemap", methods: "POST")]
    public function editSitemap(Request $request, ParamPublication $siteMap, EntityManagerInterface $entityManager):JsonResponse
    {
        $post = $request->request;
        $siteMap
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setUpdatedBy($this->getUser())
            ->setType($post->get("type") === "xml" ? "xml-sitemap" : ($post->get("type") === "rss" ? "rss-feed": "xml-sitemap"))
            ->setLocal($post->get("location"))
            ->setUrl($post->get("url"));

        $response = [];
        $response["type"] = "success";
        $response["message"] = "sitemaps modificata con successo";
        $entityManager->persist($siteMap);
        $entityManager->flush();

        return $this->json($response);
    }
}
