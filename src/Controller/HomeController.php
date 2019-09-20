<?php

namespace App\Controller;

use App\Entity\Announce;
use App\Form\SearchAnnounceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine();
        $repoAnnounce = $em->getRepository(Announce::class);
        $annonces = $repoAnnounce->findAll();
        $searchForm = $this->createForm(SearchAnnounceType::class, null, [
            'action' => $this->generateUrl('home'),
            'method' => 'GET'
        ]);

        $searchForm2 = $this->createForm(SearchAnnounceType::class);
        $searchForm2->handleRequest($request);

        if ($searchForm2->isSubmitted() && $searchForm2->isValid()){
            $em = $this->getDoctrine();
            $repoAnnounce = $em->getRepository(Announce::class);
            $data = $searchForm->getData();
            $annonces = $repoAnnounce->findForSearchSwipe($data);

            return $this->render('announce/swipe.html.twig', [
                "searchForm" => $searchForm2->createView(),
                "annonces" => $annonces
                ]);
        }
  
        if($request->isXmlHttpRequest()) {
            if ($searchForm->handleRequest($request)->isValid()) {
                $data = $searchForm->getData();

                return $this->json([
                    'html' => $this->render('home/partials/_announcement_list.html.twig', [
                        "annonces"   => $repoAnnounce->findForSearch($data),
                    ])
                ]);
            }
        }

        return $this->render('home/index.html.twig', [
            "searchForm" => $searchForm->createView(),
            'searchForm2' => $searchForm2->createView(),
            "annonces"   => $annonces,
        ]);
    }
}
