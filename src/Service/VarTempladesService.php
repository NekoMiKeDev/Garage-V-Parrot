<?php

namespace App\Service;

class VarTempladesService
{
    function varHome()
    {
        $welcomeTitle = "Bienvenue au Garage V.Parrot";
        $welcomeText = "Bienvenue au Garage V. Parrot, où l'excellence automobile prend vie ! Avec 15 années d'expertise,
        nous nous engageons à offrir des services de réparation et d'entretien exceptionnels,
        transformant chaque visite en une expérience de confiance pour votre véhicule.";

        $repairTitle = "Nos réparations";
        $repairText = "Au Garage V. Parrot,
        la mécanique est notre art, et chaque réparation est une symphonie de compétence et de dévouement.
        Explorez notre gamme complète de services de réparation de mécanique automobile,
        où la performance et la fiabilité prennent vie à chaque tour de clé à molette.";
        $repairLink = "Pour voir toutes nos réparations, ";
        $repairBodyLink = "Pour voir toutes nos réparations sur la carrosserie, ";
        $repairBodyTitle = "Réparation de carrosserie";
        $repairMechanicalLink = "Pour voir toutes nos réparations mécanique, ";
        $repairMechanicalTitle = "Réparation mécanique";

        $carTitle = "Nos véhicules d'occasions";
        $carText = "Au Garage V. Parrot, nous transcendons l'excellence automobile avec une sélection de voitures d'occasion qui reflètent notre dévouement à la qualité et à la performance.";
        $carLink = "Pour voir nos véhicules d'occasions, ";
        $commentText = "Lisez l'avis de nos clients !";
        $commentLink = "Pour nous laisser votre avis,";

        $link = " cliquez ici";

        return
            [
                'welcomeTitle' => $welcomeTitle,
                'welcomeText' => $welcomeText,
                'repairTitle' => $repairTitle,
                'repairText' => $repairText,
                'repairLink' => $repairLink,
                'repairBodyLink' => $repairBodyLink,
                'repairBodyTitle' => $repairBodyTitle,
                'repairMechanicalLink' => $repairMechanicalLink,
                'repairMechanicalTitle' => $repairMechanicalTitle,
                'carTitle' => $carTitle,
                'carText' => $carText,
                'carLink' => $carLink,
                'commentText' => $commentText,
                'commentLink' => $commentLink,
                'link' => $link,
            ];
    }

    function varMechanicalRepair()
    {
        $repairMechanicalTitle = "Réparation mécanique";
        $repairMechanicalDescription = "Nos réparation mécanique ci-dessous :";
        $repairMechanicalEndText = "Pour toutes autres questions n'hésitez pas à nous contacter via notre fomulaire de contact ";
        $linkHere = "ici";

        return
            [
                'repairMechanicalTitle' => $repairMechanicalTitle,
                'repairMechanicalDescription' => $repairMechanicalDescription,
                'repairMechanicalEndText' => $repairMechanicalEndText,
                'linkHere' => $linkHere
            ];
    }

    function varBodyRepair()
    {
        $repairBodyTitle = "Réparation de carrosserie";
        $repairBodyDescription = "Nos réparation de carrosserie ci-dessous :";
        $repairBodyEndText = "Pour toutes autres questions n'hésitez pas à nous contacter via notre fomulaire de contact ";
        $linkHere = "ici";

        return
            [
                'repairBodyTitle' => $repairBodyTitle,
                'repairBodyDescription' => $repairBodyDescription,
                'repairBodyEndText' => $repairBodyEndText,
                'linkHere' => $linkHere
            ];
    }

    function varCar()
    {
        $carTitle = "Nos voitures d'occasions";
        $carText = "";
        $carEndText = "Nous rachetons aussi les voitures, contactez-nous  ";
        $linkHere = "ici";
        return
            [
                'carTitle' => $carTitle,
                'carText' => $carText,
                'carEndText' => $carEndText,
                'linkHere' => $linkHere,
            ];
    }
}
