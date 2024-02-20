<?php

namespace App\Controller;

use App\Service\DbLoaderService;
use App\Repository\HoursRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;




class AdminController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin.index', methods:['GET'])]
    public function index(DbLoaderService $loader, HoursRepository $hoursRepository): Response
    {
        
        return $this->render('pages/admin/index.html.twig', [

            'hours' => $loader->dbload($hoursRepository),

        ]);
    }
}

