<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        $formRegister = $this->createForm(RegistrationFormType::class, $user = new User());
        $formRegister->handleRequest($request);

        if ($formRegister->isSubmitted() && $formRegister->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $formRegister->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('user/register.html.twig', [
            'controller_name' => 'UserController',
            'registrationForm' => $formRegister->createView()
        ]);
    }

    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        return $this->render('user/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/logout", name="logout", methods={"GET"})
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    /**
     * @Route("/history", name="history")
     */
    public function history()
    {
        $userConnected = $this->getUser();
        if(!empty($userConnected)) {
            $locationPast = $locationFutur = $locationDate = "";
            $idUserConnected = $userConnected->getId();
            $repository = $this->getDoctrine()->getRepository(Location::class);
            $locationPast = $repository->getLocationPast($idUserConnected);
            $locationFutur = $repository->getLocationFutur($idUserConnected);
            $locationDate = $repository->getLocationDate($idUserConnected);

            return $this->render('user/history.html.twig', [
                'locationPast' => $locationPast,
                'locationFutur' => $locationFutur,
                'locationDate' => $locationDate,
            ]);
        }

        return $this->redirectToRoute('user_login');
    }

    /**
     * @Route("/account", name="account")
     */
    public function account(Request $request){
        $userConnected = $this->getUser();
        if(!empty($userConnected)){
            $idUser = $userConnected->getId();
            $repository = $this->getDoctrine()->getRepository(User::class);
            $infoUser = $repository->find($idUser);
            $formUser = $this->createForm(UserType::class, $userConnected);
            $formUser->handleRequest($request);

            if ($formUser->isSubmitted() && $formUser->isValid()) {

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->render('user/account.html.twig', [
                    'infoUser' => $infoUser,
                    'formUser' => $formUser->createView(),
                ]);
            }

            return $this->render('user/account.html.twig', [
                'infoUser' => $infoUser,
                'formUser' => $formUser->createView(),
            ]);
        }

        return $this->redirectToRoute('user_login');
    }
}
