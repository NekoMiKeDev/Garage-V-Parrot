<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\HoursRepository;
use App\Service\DbLoaderService;
use App\Service\VarTempladesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    #[Route('/', 'home.index', methods: ['GET'])]
    public function index(CommentRepository $repository, HoursRepository $hoursRepository, DbLoaderService $loader, VarTempladesService $varTemplades): Response
    {
        $templateVariables = $varTemplades->varHome();
        $commentsValid = $repository->findBy(['isValid' => true]);
        
        return $this->render('pages/home.html.twig', [
            'commentsValid' => $commentsValid,
            'hours' => $loader->dbload($hoursRepository),
            'welcomeTitle' => $templateVariables['welcomeTitle'],
            'welcomeText' => $templateVariables['welcomeText'],
            'repairTitle' => $templateVariables['repairTitle'],
            'repairText' => $templateVariables['repairText'],
            'repairLink' => $templateVariables['repairLink'],
            'repairBodyLink' => $templateVariables['repairBodyLink'],
            'repairBodyTitle' => $templateVariables['repairBodyTitle'],
            'repairMechanicalLink' => $templateVariables['repairMechanicalLink'],
            'repairMechanicalTitle' => $templateVariables['repairMechanicalTitle'],
            'carTitle' => $templateVariables['carTitle'],
            'carText' => $templateVariables['carText'],
            'carLink' => $templateVariables['carLink'],
            'commentText' => $templateVariables['commentText'],
            'commentLink' => $templateVariables['commentLink'],
            'link' => $templateVariables['link'],
        ]);
    }
}