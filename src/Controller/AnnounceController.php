<?php

namespace App\Controller;

use App\Entity\Announce;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Form\AnnouncementType;
use App\Form\RentalType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/announce")
 */
class AnnounceController extends AbstractController
{
    /**
     * @Route("/add/vehicle", name="vehicleAnnoune")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function addVehicleAction(Request $request)
    {
        $form = $this->createForm(RentalType::class, $vehicle = new Vehicle(), [
            'action' => $this->generateUrl('vehicleAnnoune'),
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
     * @Route("/add/announcement/{vehicleId}", name="announcement")
     * @param Request $request
     * @param int $vehicleId
     * @return RedirectResponse|Response
     */
    public function addAnnouncementAction(Request $request, int $vehicleId)
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
                $announcement->setUser('test');
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
