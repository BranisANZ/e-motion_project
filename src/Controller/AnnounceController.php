<?php

namespace App\Controller;

use App\Entity\Announce;
use App\Form\AnnounceType;
use App\Form\searchAnnounceType;
use App\Repository\AnnounceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/annonce")
 */
class AnnounceController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {
        $em = $this->getDoctrine();
        $repoAnnounce = $em->getRepository(Announce::class);
        $annonces = $repoAnnounce->findAll();
        $searchForm = $this->createForm(searchAnnounceType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid()){
            $data = $searchForm->getData();
            $annonces = $repoAnnounce->findForSearch($data);
        }
        return $this->render('announce/index.html.twig', [
            "searchForm" => $searchForm->createView(),
            "annonces" => $annonces,

        ]);
    }

}
