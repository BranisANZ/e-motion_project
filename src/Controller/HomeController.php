<?php

namespace App\Controller;

use App\Entity\Announce;
use App\Form\searchAnnounceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {

        $searchForm = $this->createForm(searchAnnounceType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid()){
            $em = $this->getDoctrine();
            $repoAnnounce = $em->getRepository(Announce::class);
            $data = $searchForm->getData();
            $annonces = $repoAnnounce->findForSearch($data);

            return $this->render('announce/swipe.html.twig', [
                "searchForm" => $searchForm->createView(),
                "annonces" => $annonces
                ]);
        }


        return $this->render('home/index.html.twig', [
            'searchForm' => $searchForm->createView(),
        ]);
    }
}
