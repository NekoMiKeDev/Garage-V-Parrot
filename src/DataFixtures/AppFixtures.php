<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Car;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Hours;
use App\Entity\Repair;
use App\Entity\Comment;
use App\Entity\Contact;
use App\Entity\CarImages;
use App\Entity\PdfStorage;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create("fr_FR");
    }

    public function load(ObjectManager $manager): void
    {
        //User
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email());
            $user->setPlainPassword($this->faker->password());

            $manager->persist($user);
        }
        //REPAIR
        // Ajoutez la création d'entités Repair

        for ($i = 0; $i < 15; $i++) {
            $repair = new Repair();
            $repair->setTitle($this->faker->word());
            $repair->setRepairDescription($this->faker->text(50));
            $repairType = $this->faker->randomElement(["MECHANICAL REPAIR", "BODY REPAIR"]);
            $repair->setRepairType($repairType);
            $manager->persist($repair);
        }

        // Enregistrez toutes les entités persistées dans la base de données
        $manager->flush();
        //Contact
        for ($i = 0; $i < 10; $i++) {
            $randomBoolean = (bool) mt_rand(0, 1);

            $pNumber = '78186931';
            $pNumberShuffled = (int) "06" + str_shuffle($pNumber);

            $contact = new Contact();
            $contact->setObjet($this->faker->word());
            $contact->setLastname($this->faker->word());
            $contact->setFirstname($this->faker->word());
            $contact->setEmail($this->faker->email());
            $contact->setPhoneNumber($pNumberShuffled);
            $contact->setText($this->faker->text(150));
            $contact->setContactDone($randomBoolean);
            $manager->persist($contact);
        }
        //Comment
        for ($i = 0; $i < 20; $i++) {
            $comment = new Comment();
            $randomBoolean = (bool) rand(0, 1);
            $comment->setName($this->faker->text(rand(5, 30)));
            $comment->setComment($this->faker->realText(rand(50, 300)));
            $comment->setRate($this->faker->randomElement([1, 2, 3, 4, 5]));
            $comment->setIsValid($randomBoolean);
            $manager->persist($comment);
        }

        // Enregistrez toutes les entités persistées dans la base de données
        $manager->flush();
        $open = "08:45-12:00";
        $close = "14:00-18:00";

        $days = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];

        foreach ($days as $day) {
            $hours = new Hours();
            $hours->setDay($day);
            $hours->setOpeningHours($open);
            $hours->setClosingHours($close);
            $manager->persist($hours);
        }

        $admin = new User();
        $admin->setEmail('garagevparrot@garage.pr');
        $admin->setPlainPassword('admin');
        $admin->setRoles(['ROLE_ADMIN', '[ROLE_USER]']);
        $manager->persist($admin);

        $employee = new User();
        $employee->setEmail('employeeparrot@garage.pr');
        $employee->setPlainPassword('employee');
        $manager->persist($employee);

        $manager->flush();

        $manufactureDate = $this->faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d');
        $dateTime = \DateTime::createFromFormat('Y-m-d', $manufactureDate);

        for ($i = 0; $i < 10; $i++) {
            $car = new Car();
            $car->setModel($this->faker->word());
            $car->setDescription($this->faker->text(150));
            $car->setPrice($this->faker->randomFloat(2, 1000, 100000));
            $car->setDateOfManufacture($dateTime);
            $car->setMileage($this->faker->numberBetween(0, 500000));

            // Persistez l'entité Car
            $manager->persist($car);
            $manager->flush(); // Flush pour générer l'ID du Car

            // Créez une instance de CarImages pour chaque voiture
            $carImg = new CarImages();
            $carImg->setCar($car);
            $carImg->setImageFile($this->createUploadedFile());

            // Chemin du fichier PDF
            $pdfFilePath = 'public/uploads/pdfs/GarageParrotWireframeCopie('. $i .').pdf';

            // Extraire le nom de fichier et le type de fichier à partir du chemin du fichier
            $fileName = pathinfo($pdfFilePath, PATHINFO_BASENAME);
            $fileType = mime_content_type($pdfFilePath);

            // Créez une instance de PdfStorage pour chaque voiture
            $pdfStorage = new PdfStorage();
            $pdfStorage->setFileName($fileName);
            $pdfStorage->setFileType($fileType);
            $pdfStorage->setUploadDate(new \DateTimeImmutable());
            $pdfStorage->setCarPdf($car);

            // Persistez l'entité PdfStorage
            $manager->persist($pdfStorage);



            // Persistez l'entité CarImages
            $manager->persist($carImg);
        }

        $manager->flush();
    }

    private function createUploadedFile(): UploadedFile
    {
        $absolutePath = "C:\Users\alexi\Downloads\capture-d-ecran-1-65bc20dc2db9e379747700 (3).png";
        $tmpPath = sys_get_temp_dir() . '/uploaded_file.tmp';
        copy($absolutePath, $tmpPath);

        return new UploadedFile($tmpPath, 'uploaded_file.png', 'image/png', null, true);
    }
}
