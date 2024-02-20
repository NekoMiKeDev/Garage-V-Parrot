<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Service\DbLoaderService;
use App\Repository\HoursRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CommentController extends AbstractController
{

    #[IsGranted('ROLE_USER')]
    #[Route('/comment', name: 'comment.index', methods: ['POST', 'GET'])]
    public function employeeComment(CommentRepository $commentRepository, DbLoaderService $loader, HoursRepository $hoursRepository): Response
    {
        return $this->render('pages/comment/index.html.twig', [
            "validComments" => $loader->dbload($commentRepository, 'VALIDCOMMENTS'),
            "notValidComments" => $loader->dbload($commentRepository, 'NOTVALIDCOMMENTS'),
            'hours' => $loader->dbload($hoursRepository),

        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/comment/delete/{id}', name: 'comment.delete', methods: ['GET'])]
    public function delete(UserInterface $checkUser, EntityManagerInterface $manager, Comment $comment): Response
    {
        if (!$comment) {
            $this->addFlash('miss', "Le commentaire n'a pas été trouvé.");
            if ($this->isGranted('ROLE_ADMIN', $checkUser)) {
                return $this->redirectToRoute('admin.index');
            } elseif ($this->isGranted('ROLE_USER', $checkUser)) {
                return $this->redirectToRoute('employee.index');
            }
        }

        $manager->remove($comment);
        $manager->flush();
        $this->addFlash('success', 'Le commentaire à bien été supprimé.');

        return $this->redirectToRoute('comment.index');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/comment/isValid/{id}', name: 'comment.isValid', methods: ['GET', 'POST'])]
    public function isValid(EntityManagerInterface $manager, Comment $comment): Response
    {
        if ($comment) {
            $comment->setIsValid(true);
            $manager->flush();
            $this->addFlash("success", "Le commentaire est validé.");
        }
        return $this->redirectToRoute('comment.index');
    }

    #[Route('/comment/create', name: 'comment.create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $manager, DbLoaderService $loader, HoursRepository $hoursRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);

            $manager->flush();
            $this->addFlash("success", "Le commentaire a bien été ajouté.");;
            return  $this->redirectToRoute('home.index');
        }


        return $this->render('pages/comment/create.html.twig', [
            'form' => $form,
            'hours' => $loader->dbload($hoursRepository),
        ]);
    }
}
