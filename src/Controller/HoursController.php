<?php

namespace App\Controller;

use App\Entity\Hours;
use App\Form\HoursType;
use App\Service\DbLoaderService;
use App\Repository\HoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HoursController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/hours', name: 'hours.index', methods: ['GET', 'POST'])]
    public function index(DbLoaderService $loader, HoursRepository $hoursRepository, Request $request, EntityManagerInterface $manager)
    {
        if ($request->isMethod('POST')) {
            $days = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
    
            $existingHours = $hoursRepository->findAll();
    
            foreach ($existingHours as $existingHour) {
                $day = $existingHour->getDay();
    
                $dayIndex = array_search($day, $days);
    
                if ($dayIndex !== false) {
                    if ($day === 'dimanche') {
                        $existingHour->setOpeningHours("fermé");
                        $existingHour->setClosingHours("fermé");
                    } elseif ($day === 'samedi') {
                        $existingHour->setOpeningHours("08:45-12:00");
                        $existingHour->setClosingHours("fermé");
                    } else {
                        $existingHour->setOpeningHours("08:45-12:00");
                        $existingHour->setClosingHours("14:00-18:00");
                    }
                }
            }
            $manager->flush();
    
            return $this->redirectToRoute('admin.index');
        }
    
        return $this->render('pages/hours/index.html.twig', [
            'hours' => $loader->dbload($hoursRepository),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/hours/{id}', name: 'hours.update', methods:['GET', 'POST'])]
    public function update(Hours $hour, DbLoaderService $loader, HoursRepository $hoursRepository, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(HoursType::class, $hour);
        
        $form->handleRequest($request);

        $results = $hoursRepository->findAll();
        $associativeArray = [];
        $urlId = $request->attributes->get('id');

        foreach ($results as $result) {
            $id = $result->getId(); 
            $day = $result->getDay();
            $associativeArray[$id] = $day;
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            $hour->setDay($associativeArray[$urlId]);
            $manager->persist($hour);
            $manager->flush();

            $this->addFlash(
                'successHour',
                "Changement d'horraire d'ouverture bien effectué"
            );

            return $this->redirectToRoute('hours.index');
        }

        return $this->render('pages/hours/update.html.twig', [
            'form' => $form->createView(),
            'hours' => $loader->dbload($hoursRepository),
            'days' => $associativeArray,
            'urlId' => $urlId
        ]);
    }
}
