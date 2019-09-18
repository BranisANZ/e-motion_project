<?php

namespace App\Controller;

use App\Form\searchAnnounceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{
    RedirectResponse, Request, Response
};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

use App\Entity\{
    Announce, Vehicle
};
use App\Form\{
    AnnouncementType, RentalType
};
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

    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/add/vehicle", name="vehicleAnnounce")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function addVehicleAction(Request $request)
    {
        $form = $this->createForm(RentalType::class, $vehicle = new Vehicle(), [
            'action' => $this->generateUrl('vehicleAnnounce'),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isCsrfTokenValid(
                'rental_item',
                $request->request->get('rental')['_token']
            )) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($vehicle);
                $em->flush();

                return $this->redirectToRoute('announcement', [
                    'vehicleId' => $vehicle->getId(),
                ]);
            }
        }
        return $this->render("announcement/partials/_vehicle.html.twig", array(
            'form'  => $form->createView(),
        ));
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/add/announcement/{vehicleId}", name="announcement")
     * @param Security $security
     * @param Request $request
     * @param int $vehicleId
     * @return RedirectResponse|Response
     */
    public function addAnnouncementAction(Security $security, Request $request, int $vehicleId)
    {
        $form = $this->createForm(AnnouncementType::class, $announcement = new Announce(), [
            'action' => $this->generateUrl('announcement', [
                'vehicleId' => $vehicleId,
            ]),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isCsrfTokenValid(
                'announcement_item',
                $request->request->get('announcement')['_token']
            )) {
                $em = $this->getDoctrine()->getManager();
                $repo = $em->getRepository(Vehicle::class);

                $announcement->setVehicle($repo->find($vehicleId));
                $announcement->setUser($security->getUser());
                $em->persist($announcement);
                $em->flush();

                return $this->redirectToRoute("home");
            }
        }
        return $this->render("announcement/partials/_announcement.html.twig", array(
            'form'  => $form->createView(),
        ));
    }
}
