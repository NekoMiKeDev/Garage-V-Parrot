<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\DbLoaderService;
use App\Repository\HoursRepository;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ContactController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/contact', name: 'contact.index', methods: ['GET'])]
    public function index(DbLoaderService $loader, HoursRepository $hours, ContactRepository $contactRepository): Response
    {
        $contacts = $contactRepository->findBy(['contactDone' => false]);
        return $this->render('pages/contact/index.html.twig', [
            'contacts' => $contacts,
            'hours' => $loader->dbload($hours)
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/contact/old', name: 'contact.old', methods: ['GET'])]
    public function indexOfOldContact(DbLoaderService $loader, HoursRepository $hours, ContactRepository $contactRepository): Response
    {
        $oldContacts = $contactRepository->findBy(['contactDone' => true]);

        return $this->render('pages/contact/oldContact.html.twig', [
            'oldContacts' => $oldContacts,
            'hours' => $loader->dbload($hours)
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/contact/delete/{id}', name: 'contact.delete', methods: ['GET', 'POST'])]
    public function delete(EntityManagerInterface $manager, ContactRepository $contactRepository, int $id): Response
    {
        $contact = $contactRepository->find($id);

        if (!$contact) {
            return $this->redirectToRoute('home.index');
            $this->addFlash(
                'miss',
                "Le contact n'a pas été trouvé"
            );
        }

        $manager->remove($contact);
        $manager->flush();

        $this->addFlash(
            'success',
            'Le contact a bien été effacé'
        );

        if ($contact->getContactDone() === false) {
            return $this->redirectToRoute('contact.index');
        } else {
            return $this->redirectToRoute('contact.old');
        }
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/contact/{id}/validate', name: 'contact.validate', methods: ['POST'])]
    public function validateContact(Request $request, EntityManagerInterface $manager, $id): Response
    {
        $contact = $manager->getRepository(Contact::class)->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('Contact non trouvé');
        }


        if ($request->request->has('valider_contact')) {

            if ($contact->getContactDone() === false) {
                $contact->setContactDone(true);
                $this->addFlash("success", "le contact à bien été validé !");
            } else {
                $contact->setContactDone(false);
                $this->addFlash("success", "la validation du contact à bien été annulé !");
            }

            $manager->persist($contact);
            $manager->flush();

            if ($contact->getContactDone() === false) {
                return $this->redirectToRoute('contact.index');
            } else {
                return $this->redirectToRoute('contact.index');
            }
        }

        return new Response('Action non autorisée', Response::HTTP_FORBIDDEN);
    }

    #[Route('/contact/form', name: 'contact.form', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $manager, DbLoaderService $loader, HoursRepository $hours): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {


                $manager->persist($contact);
                $manager->flush();

                $this->addFlash("success", "Le contact a bien été créé ! ");

                return $this->redirectToRoute('home.index');
            } else {
                // Récupérer les erreurs de formulaire
                $errors = $form->getErrors(true, true);

                foreach ($errors as $error) {
                    $this->addFlash("miss", $error->getCause()->getMessage());
                    return $this->redirectToRoute('contact.form');
                }

                return $this->redirectToRoute('home.index');
            }
        }

        return $this->render('pages/contact/form.html.twig', [
            'form' => $form->createView(),
            'hours' => $loader->dbload($hours)
        ]);
    }
}
