<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\DbLoaderService;
use App\Repository\HoursRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/user', name: 'user.index', methods: ['GET', 'POST'])]
    public function index(DbLoaderService $loader, HoursRepository  $hoursRepository, UserRepository $userRepository): Response
    {

        return $this->render('pages/user/index.html.twig', [
            'users' => $loader->dbload($userRepository),
            'hours' => $loader->dbload($hoursRepository)
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/user/update/{id}', name: 'user.update', methods: ['GET', 'POST'])]
    public function edit(User $user, Request $request, EntityManagerInterface $manager, HoursRepository $hoursRepository, DbLoaderService $loader): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($plainPassword);
            
            $manager->persist($user);
            $manager->flush();

            $this->addFlash("success", "Le compte utilisateur à bien été modifié ");
            return $this->redirectToRoute('user.index');
        } else {
            $errors = $form->getErrors(true, true);

            foreach ($errors as $error) {
                $this->addFlash("miss", $error->getCause()->getMessage());
                return $this->redirectToRoute('user.index');
            }
        }

        return $this->render('pages/user/update.html.twig', [
            'form' => $form->createView(),
            'users' => $user,
            'hours' => $loader->dbload($hoursRepository)
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/user/delete/{id}', name: 'user.delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, User $user): Response
    {
        if (!$user) {
            $this->addFlash("miss", "Le compte utilisateur n'a pas été trouvé.");
            return $this->redirectToRoute('user.index');
        }
        $manager->remove($user);
        $manager->flush();

        $this->addFlash("success", "Le compte utilisateur à bien été supprimé.");

        return $this->redirectToRoute('user.index');
    }
}
