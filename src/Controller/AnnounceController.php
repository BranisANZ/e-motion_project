<?php

namespace App\Controller;

use App\Entity\Announce;
use App\Form\AnnounceType;
use App\Repository\AnnounceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/announce")
 */
class AnnounceController extends AbstractController
{
    /**
     * @Route("/", name="announce_index", methods={"GET"})
     */
    public function index(AnnounceRepository $announceRepository): Response
    {
        return $this->render('announce/register.html.twig', [
            'announces' => $announceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="announce_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $form = $this->createForm(AnnounceType::class, $announce = new Announce());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($announce);
            $entityManager->flush();

            return $this->redirectToRoute('announce_index');
        }

        return $this->render('announce/new.html.twig', [
            'announce' => $announce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="announce_show", methods={"GET"})
     */
    public function show(Announce $announce): Response
    {
        return $this->render('announce/show.html.twig', [
            'announce' => $announce,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="announce_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Announce $announce): Response
    {
        $form = $this->createForm(AnnounceType::class, $announce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('announce_index');
        }

        return $this->render('announce/edit.html.twig', [
            'announce' => $announce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="announce_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Announce $announce): Response
    {
        if ($this->isCsrfTokenValid('delete'.$announce->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($announce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('announce_index');
    }
}
