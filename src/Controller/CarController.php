<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Entity\CarImages;
use App\Entity\PdfStorage;
use App\Service\DbLoaderService;
use App\Repository\CarRepository;
use App\Repository\HoursRepository;
use App\Service\VarTempladesService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class CarController extends AbstractController

{
    #[IsGranted('ROLE_USER')]
    #[Route('/moderation/car', name: 'moderation.car', methods: ['GET', 'POST'])]
    public function indexCar(CarRepository $carRepository, HoursRepository $hoursRepository, DbLoaderService $loader, Request $request,  PaginatorInterface $paginator): Response
    {

        $query = $carRepository->createQueryBuilder('c')->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/car/moderation.html.twig', [
            'hours' => $loader->dbload($hoursRepository),
            'cars' => $pagination,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/car/create', name: 'car.create', methods: ["GET", "POST"])]
    public function create(Request $request, EntityManagerInterface $manager, DbLoaderService $loader, HoursRepository $hoursRepository, CarRepository $carRepository): Response
    {
        $form = $this->createForm(CarType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $model = $form->get('model')->getData();
            $description = $form->get('description')->getData();
            $dateOfManufacture = $form->get('dateOfManufacture')->getData();
            $mileage = $form->get('mileage')->getData();
            $price = $form->get('price')->getData();


            $car = new Car();
            $car->setModel($model);
            $car->setDescription($description);
            $car->setMileage($mileage);
            $car->setPrice($price);
            $car->setDateOfManufacture($dateOfManufacture);
            $manager->persist($car);
            // Récupérez les fichiers du champ inputFile
            $files = $request->files->get('car')['inputFile'];
            $uploadedFile = $request->files->get('car')['pdfFile'];

            if ($files) {
                foreach ($files as $file) {
                    $files = $file;
                    // Créez une nouvelle instance de CarImages pour chaque fichier
                    $carImages = new CarImages();
                    $carImages->setCar($car);
                    $carImages->setImageFile($files);

                    // Enregistrez l'entité CarImages dans la base de données
                    $manager->persist($carImages);
                }
            }

            if ($uploadedFile) {

                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/pdfs';;
                $fileName = $uploadedFile->getClientOriginalName();
                $fileType = $uploadedFile->getClientMimeType();
                $uploadedFile->move($destination, $fileName);

                if ($fileType === 'application/pdf') {

                    $pdfStorage = new PdfStorage();
                    $pdfStorage->setFileName($fileName);
                    $pdfStorage->setFileType($fileType);
                    $pdfStorage->setUploadDate(new \DateTimeImmutable());
                    $pdfStorage->setCarPdf($car);
                    $manager->persist($pdfStorage);
                }
            }

            $manager->flush();
            $this->addFlash("success", "La voiture d'occasion a bien été ajouté");
            return $this->redirectToRoute('moderation.car');
        } else {
            $errors = $form->getErrors(true, true);

            foreach ($errors as $error) {
                $this->addFlash("miss", $error->getCause()->getMessage());
                return $this->redirectToRoute('moderation.car');
            }
        }

        return $this->render('pages/car/create.html.twig', [
            'form' => $form->createView(),
            'hours' => $loader->dbload($hoursRepository),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/car/update/{id}', name: 'car.update', methods: ['GET', 'POST'])]
    public function edit(Car $car, Request $request, EntityManagerInterface $manager, DbLoaderService $loader, HoursRepository $hoursRepository): Response
    {
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            // Récupérez les fichiers du champ inputFile
            $files = $request->files->get('car')['inputFile'];
            $uploadedFile = $request->files->get('car')['pdfFile'];

            $manager->persist($car);
            if ($files) {
                foreach ($files as $file) {
                    $files = $file;
                    // Créez une nouvelle instance de CarImages pour chaque fichier
                    $carImages = new CarImages();
                    $carImages->setCar($car);
                    $carImages->setImageFile($files);

                    // Enregistrez l'entité CarImages dans la base de données
                    $manager->persist($carImages);
                }
            }

            if ($uploadedFile) {

                $tabl = $car->getPdfStorages();

                foreach ($tabl as $pdfStorage) {
                    $car->removePdfStorage($pdfStorage);
                    $manager->remove($pdfStorage); 
                
                }

                $manager->flush(); 

                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/pdfs';
                $fileName = $uploadedFile->getClientOriginalName();
                $fileType = $uploadedFile->getClientMimeType();
                $uploadedFile->move($destination, $fileName);
            
                if ($fileType === 'application/pdf') {
                    // Créer une nouvelle instance de PdfStorage
                    $pdfStorage = new PdfStorage();
                    $pdfStorage->setFileName($fileName);
                    $pdfStorage->setFileType($fileType);
                    $pdfStorage->setUploadDate(new \DateTimeImmutable());
                    $pdfStorage->setCarPdf($car);
                    
                    // Ajouter le nouveau PDF à la collection
                    $car->addPdfStorage($pdfStorage);
            
                    // Enregistrez l'entité PdfStorage dans la base de données
                    $manager->persist($pdfStorage);
                }
            } 

            $manager->flush();
            $this->addFlash("success", "La voiture d'occasion a bien été modifié");
            return $this->redirectToRoute('moderation.car');
        }else {
            $errors = $form->getErrors(true, true);

            foreach ($errors as $error) {
                $this->addFlash("miss", $error->getCause()->getMessage());
                return $this->redirectToRoute('moderation.car');
            }
        }
        
        return $this->render('/pages/car/update.html.twig', [
            'form' => $form->createView(),
            'car' => $car,
            'hours' => $loader->dbload($hoursRepository),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/car/delete/{id}', name: 'car.delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Car $car): Response
    {
        if (!$car) {
            $this->addFlash("miss", "La voiture d'occasion a bien été mise à en ligne");
            return $this->redirectToRoute('moderation.car');
        }
        $manager->remove($car);
        $manager->flush();

        $this->addFlash("success", "La voiture d'occasion a bien été supprimé");

        return $this->redirectToRoute('moderation.car');
    }

    #[Route('/car', name: 'car.index', methods: ["GET", "POST"])]
    public function index(CarRepository $repository, PaginatorInterface $paginator, Request $request, DbLoaderService $loader, HoursRepository $hoursRepository, VarTempladesService $varTemplades): Response
    {
        $templateVariables = $varTemplades->varCar();

        $cars = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt("page", 1),
            9
        );
        return $this->render('pages/car/index.html.twig', [
            'cars' => $cars,
            'hours' => $loader->dbload($hoursRepository),
            'carTitle' => $templateVariables['carTitle'],
            'carText' => $templateVariables['carText'],
            'carEndText' => $templateVariables['carEndText'],
            'linkHere' => $templateVariables['linkHere'],
        ]);
    }

    #[Route('/car/gallery/{id}', name: 'car.gallery', methods: ['GET'])]
    public function show(Car $car, HoursRepository $hoursRepository, DbLoaderService $loader): Response
    {
        if (!$car) {
            throw $this->createNotFoundException('Car not found');
        } else {
            $gallery = $car->getCarImages();
        }

        return $this->render('pages/car/displayGallery.html.twig', [
            'hours' => $loader->dbload($hoursRepository),
            'car' => $car,
            'gallery' => $gallery,
        ]);
    }

    #[Route('/filter', name: 'car.filter', methods: ['GET', 'POST'])]
    public function carFilter(Request $request, CarRepository $cars, SerializerInterface $serializer, UploaderHelper $uploaderHelper): Response
    {
        $jsonData = json_decode($request->getContent(), true);

        if (isset($jsonData['price'])) {
            $price = floatval($jsonData['price']);

            if (!is_float($price)) {
                return $this->json(['code' => 400, 'message' => 'La valeur de price n\'est pas un nombre'], 400);
            }

            // Utilisez la méthode findByPrice pour obtenir les voitures filtrées
            $filteredCars = $cars->findByPrice($price);
            $imageUrls = [];
            foreach ($filteredCars as $car) {
                $carImages = $car->getCarImages();

                // Vérifiez si $carImages est différent de null avant de parcourir la boucle
                if ($carImages !== null) {
                    foreach ($carImages as $carImage) {
                        // Utilisez la méthode de VichUploaderBundle pour obtenir l'URL de l'image
                        $imageUrl = $uploaderHelper->asset($carImage, 'imageFile');
                        $imageUrls[] = $imageUrl;
                    }
                } else {
                    // Si $carImages est null, ajoutez une URL par défaut
                    $imageUrls[] = 'public//uploads/images/HS1.png';
                }
            }

            // Serializez les voitures en utilisant le groupe de sérialisation spécifié
            $jsonResponse = $serializer->serialize([
                'code' => 200,
                'message' => 'Requête price réussie!',
                'cars' => $filteredCars,
                'imageUrls' => $imageUrls,
            ], 'json', ['groups' => 'car']);

            return new JsonResponse($jsonResponse, 200, [], true);
        }

        return $this->json(['code' => 400, 'message' => 'Données manquantes'], 400);
    }

    #[Route('/search', name: 'car.search', methods: ['POST'])]
    public function carSearch(Request $request, CarRepository $cars, SerializerInterface $serializer, UploaderHelper $uploaderHelper): Response
    {
        $jsonData = json_decode($request->getContent(), true);
    
        if (isset($jsonData['search'])) {
            $search = $jsonData['search'];
    
            // Utilisez la méthode findBySearch pour obtenir les voitures filtrées
            $searchedCars = $cars->findBySearch($search);
            $imageUrls = [];
    
            foreach ($searchedCars as $car) {
                $carImages = $car->getCarImages();
    
                // Vérifiez si $carImages est différent de null avant de parcourir la boucle
                if ($carImages !== null) {
                    foreach ($carImages as $carImage) {
                        // Utilisez la méthode de VichUploaderBundle pour obtenir l'URL de l'image
                        $imageUrl = $uploaderHelper->asset($carImage, 'imageFile');
                        $imageUrls[] = $imageUrl;
                    }
                } else {
                    // Si $carImages est null, ajoutez une URL par défaut
                    $imageUrls[] = 'public//uploads/images/HS1.png';
                }
            }
    
            // Serializez les voitures en utilisant le groupe de sérialisation spécifié
            $jsonResponse = $serializer->serialize([
                'code' => 200,
                'message' => 'Requête de recherche réussie!',
                'cars' => $searchedCars,
                'imageUrls' => $imageUrls,
            ], 'json', ['groups' => 'car']);
    
            return new JsonResponse($jsonResponse, 200, [], true);
        }
    
        return $this->json(['code' => 400, 'message' => 'Données manquantes'], 400);
    }
}
