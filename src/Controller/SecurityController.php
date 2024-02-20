<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Service\DbLoaderService;
use App\Repository\HoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'security.login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils, HoursRepository $hoursRepository, DbLoaderService $loader): Response
    {

        return $this->render('pages/security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'hours' => $loader->dbload($hoursRepository)
        ]);
    }

    #[Route('/logout', name: 'security.logout', methods: ['GET'])]
    public function logout()
    {
        // Nothing to do here..
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/registration', name: 'security.registration', methods: ['GET', 'POST'])]
    public function registration(EntityManagerInterface $manager, Request $request, User $user, HoursRepository $hoursRepository, DbLoaderService $loader, SessionInterface $session): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($plainPassword);
            $manager->persist($user);
            $manager->flush();
    
            $this->addFlash("success", "L'utilisateur a bien été ajouté.");
    
            return $this->redirectToRoute('admin.index');
        } else {
            $errors = $form->getErrors(true, true);
    
            foreach ($errors as $error) {
                $this->addFlash("miss", $error->getCause()->getMessage());
            }
        }
    
        // Le rendu du formulaire en dehors de la condition
        return $this->render('pages/security/registration.html.twig', [
            'form' => $form->createView(),
            'hours' => $loader->dbload($hoursRepository)
        ]);
    }
}
