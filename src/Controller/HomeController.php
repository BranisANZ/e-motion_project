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
            "annonces"   => $annonces,
        ]);
    }
}
