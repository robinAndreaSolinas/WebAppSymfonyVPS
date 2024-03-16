<?php

namespace App\Controller;

use App\Repository\PublicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ajax/', name: 'ajax_', condition: "request.isXmlHttpRequest()")]
class AjaxController extends AbstractController
{

    #[Route('get-data', name: 'data', methods: "GET")]
    public function publicationData(PublicationRepository $publicationRepository): JsonResponse
    {
        $data = $publicationRepository
            ->createQueryBuilder("data")
            ->getQuery()
            ->getArrayResult();

        return $this->json(["data" => $data]);
    }
}
