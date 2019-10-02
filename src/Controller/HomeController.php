<?php

namespace App\Controller;

use App\Entity\Announce;
use App\Form\SearchAnnounceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
        $em           = $this->getDoctrine()->getManager();
        $repoAnnounce = $em->getRepository(Announce::class);
        $annonces     = $repoAnnounce->findAll();
        $searchForm   = $this->createForm(SearchAnnounceType::class, null, [
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

    /**
     * @Route("/eSwipe", name="eSwipe")
     * @Template("modal/_modal-form-e-swip.html.twig")
     * @param Request $request
     * @return Response
     */
    public function eSwipe(Request $request) {
        $em           = $this->getDoctrine()->getManager();
        $repoAnnounce = $em->getRepository(Announce::class);
        $searchForm  = $this->createForm(SearchAnnounceType::class, null, [
            'action' => $this->generateUrl('eSwipe'),
            'method' => 'POST'
        ]);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()){
            $data     = $searchForm->getData();
            $annonces = $repoAnnounce->findForSearchSwipe($data);

            return $this->render('announce/swipe.html.twig', [
                "searchForm" => $searchForm->createView(),
                "annonces"   => $annonces
            ]);
        }

        $searchForm = $searchForm->createView();

        return compact('searchForm');
    }
}
