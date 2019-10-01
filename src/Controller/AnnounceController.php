<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{
    RedirectResponse, Request, Response
};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

use App\Entity\{Announce, User, Vehicle};
use App\Form\{
    AnnouncementType, RentalType
};
/**
 * @Route("/annonce")
 */
class AnnounceController extends AbstractController
{
    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/add/vehicle", name="vehicleAnnounce")
     * @param Security $security
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function addVehicleAction(Security $security, Request $request)
    {
        $form = $this->createForm(RentalType::class, $vehicle = new Vehicle(), [
            'action' => $this->generateUrl('vehicleAnnounce'),
            'method' => 'POST'
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isCsrfTokenValid(
                'rental_item',
                $request->request->get('rental')['_token']
            )) {
                /** @var User $user */
                $user        = $security->getUser();
                $em          = $this->getDoctrine()->getManager();
                $repoVehicle = $em->getRepository(Vehicle::class);

                if (!$vehicleBDD = $repoVehicle->findOneBy([

                    'user'          => $user,
                    'matriculation' => $vehicle->getMatriculation()
                ])) {
                    $file     = $form->get('photo')->getData();
                    $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
                    $file->move(
                        $this->getParameter('brochures_directory'),
                        $fileName
                    );

                    $vehicle->setPhoto($fileName);
                    $vehicle->setUser($user);
                    $em->persist($vehicle);
                    $em->flush();

                    return $this->redirectToRoute('announcement', [
                        'vehicleId' => $vehicle->getId(),
                    ]);
                }

                return $this->redirectToRoute('announcement', [
                    'vehicleId' => $vehicleBDD->getId(),
                ]);
            }
        }
        return $this->render("announce/partials/_vehicle.html.twig", array(
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
        return $this->render("announce/partials/_announcement.html.twig", array(
            'form'  => $form->createView(),
        ));
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}
