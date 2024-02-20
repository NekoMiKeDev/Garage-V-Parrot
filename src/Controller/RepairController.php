<?php

namespace App\Controller;

use App\Entity\Repair;
use App\Form\RepairType;
use App\Service\DbLoaderService;
use App\Repository\HoursRepository;
use App\Repository\RepairRepository;
use App\Service\VarTempladesService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RepairController extends AbstractController
{

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/repair/create', name: 'repair.create', methods: ["GET", "POST"])]
    public function create(Request $request, EntityManagerInterface $manager, HoursRepository $hoursRepository, DbLoaderService $loader): Response
    {
        $repair = new Repair();
        $form = $this->createForm(RepairType::class, $repair);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($repair);
            $manager->flush();

            $this->addFlash('success', 'La réparation à bien été ajouter.');

            return $this->redirectToRoute('admin.repair');
        }

        return $this->render('pages/repair/create.html.twig', [
            'form' => $form,
            'hours' => $loader->dbload($hoursRepository)
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/repair/update/{id}', name: 'repair.update', methods: ['GET', 'POST'])]
    public function update(Repair $repair, Request $request, EntityManagerInterface $manager, HoursRepository $hoursRepository, DbLoaderService $loader): Response
    {
        $form = $this->createForm(RepairType::class, $repair);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($repair);
            $manager->flush();

            $this->addFlash('success', 'La réparation à bien été modifier.');

            return $this->redirectToRoute('admin.repair');
        }

        return $this->render('pages/repair/update.html.twig', [
            'form' => $form,
            'hours' => $loader->dbload($hoursRepository)
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/repair/delete/{id}', name: 'repair.delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Repair $repair): Response
    {
        if (!$repair) {
            $this->addFlash('miss', "La réparation n'a pas été trouvé.");
            return $this->redirectToRoute('admin.repair');
        }
        $manager->remove($repair);
        $manager->flush();

        $this->addFlash('success', 'La réparation à bien été supprimer.');

        return $this->redirectToRoute('admin.repair');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/repair', name: 'admin.repair', methods: ['GET', 'POST'])]
    public function indexRepair(RepairRepository $repairsRepository, HoursRepository $hoursRepository, DbLoaderService $loader): Response
    {

        return $this->render('pages/repair/adminRepair.html.twig', [
            'hours' => $loader->dbload($hoursRepository),
            'repairs' => $loader->dbload($repairsRepository, 'REPAIR'),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/mechanical', name: 'admin.mechanical', methods: ['GET', 'POST'])]
    public function indexAdminMechanical(RepairRepository $repairsRepository, HoursRepository $hoursRepository, DbLoaderService $loader): Response
    {


        return $this->render('pages/repair/adminMechanicalRepair.html.twig', [
            'hours' => $loader->dbload($hoursRepository),
            'mechanicals' => $loader->dbload($repairsRepository, 'MECHANICAL'),
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/body', name: 'admin.body', methods: ['GET', 'POST'])]
    public function indexAdminBody(RepairRepository $repairsRepository, HoursRepository $hoursRepository, DbLoaderService $loader): Response
    {


        return $this->render('pages/repair/adminBodyRepair.html.twig', [
            'hours' => $loader->dbload($hoursRepository),
            'bodys' => $loader->dbload($repairsRepository, 'REPAIR'),
        ]);
    }

    #[Route('/mechanical', name: 'mechanical.index', methods: ['GET'])]
    public function indexMechanical(RepairRepository $repository, HoursRepository $hoursRepository, DbLoaderService $loader, VarTempladesService $varTemplades): Response
    {
        $templateVariables = $varTemplades->varMechanicalRepair();
        $mechanical = $loader->dbload($repository, 'MECHANICAL');

        return $this->render('pages/repair/mechanicalIndex.html.twig', [
            'hours' => $loader->dbload($hoursRepository),
            'repairMechanical' => $mechanical,
            'repairMechanicalTitle' => $templateVariables['repairMechanicalTitle'],
            'repairMechanicalDescription' => $templateVariables['repairMechanicalDescription'],
            'repairMechanicalEndText' => $templateVariables['repairMechanicalEndText'],
            'linkHere' => $templateVariables['linkHere'],
        ]);
    }

    #[Route('/body', name: 'body.index', methods: ['GET'])]
    public function indexBody(RepairRepository $repository, HoursRepository $hoursRepository, DbLoaderService $loader, VarTempladesService $varTemplades): Response
    {
        $templateVariables = $varTemplades->varBodyRepair();
        $body = $loader->dbload($repository, 'BODY');

        return $this->render('pages/repair/bodyIndex.html.twig', [
            'hours' => $loader->dbload($hoursRepository),
            'repairBody' => $body,
            'repairBodyTitle' => $templateVariables['repairBodyTitle'],
            'repairBodyDescription' => $templateVariables['repairBodyDescription'],
            'repairBodyEndText' => $templateVariables['repairBodyEndText'],
            'linkHere' => $templateVariables['linkHere'],
        ]);
    }
}
