<?php

namespace App\Service;


use App\Repository\CarRepository;
use App\Repository\UserRepository;
use App\Repository\HoursRepository;
use App\Repository\RepairRepository;
use App\Repository\CommentRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;



class DbLoaderService
{


    public function __construct()
    {
    }

    //load db check Type of variable and return db
    function dbload($repository, $selector = null)
    {
        if ($repository instanceof UserRepository) {
            // Traitement pour UserRepository
            return $repository->findAll();
        } elseif ($repository instanceof RepairRepository) {
            // Traitement pour RepairRepository
            if ($selector == "REPAIR") {
                $repairs = $repository->findAll();
                return $repairs;
            } else if ($selector == 'BODY') {
                $repairBody = $repository->findBy(['repairType' => "Body Repair"]);
                return $repairBody;
            } else if ($selector == 'MECHANICAL') {
                $repairMechanical = $repository->findBy(['repairType' => "Mechanical Repair"]);
                return $repairMechanical;
            } else {
                dump("selector incorrect");
            }
        } elseif ($repository instanceof CarRepository) {
            // Traitement pour CarRepository
            return $repository->findAll();
        } elseif ($repository instanceof CommentRepository) {
            if ($selector === "VALIDCOMMENTS") {
                return $repository->findBy(['isValid' => '1']);
            } else if ($selector === "NOTVALIDCOMMENTS") {
                return $selector = $repository->findBy(['isValid' => '0']);
            }
        } elseif ($repository instanceof HoursRepository) {
            return $repository->findAll();
        } else {
            // Aucun repository valide fourni, générer une erreur
            trigger_error('Type de repository non valide spécifié.', E_USER_ERROR);
            return null;
        }
    }
}
