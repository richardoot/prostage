<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Entreprise;
use App\Entity\Formation;
use App\Entity\Stage;
use App\Entity\User;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
      //Création générateur de données Faker
        $faker = \Faker\Factory::create('fr_FR');


      //------------------USERS------------------//

      $user = new User();

      $user->setPrenom('Richard');
      $user->setNom('Boilley');
      $user->setEmail('rico@gmail.com');
      $user->setPassword('$2y$10$Ula3GBU5Qn4o.udg.PF7yeJWYIiwWKCptDpk9Uvc5gosXRCmfAefK');
      $user->setRoles(['ROLE_USER','ROLE_ADMIN']);

      $manager->persist($user);
      $manager->flush();

      //---------------FORMATIONS---------------//
        //Variable

          $formation = new Formation();
          $nbFormations = 10;
          $diplome[] = "DUT";
          $diplome[] = "BTS";
          $diplome[] = "License";
          $diplome[] = "Master";
          $diplome[] = "Doctorat";

          $secteur[] = "Informatique";
          $secteur[] = "GEA";
          $secteur[] = "Tech de CO";
          $secteur[] = "Communication";
          $secteur[] = "Bio";
          $secteur[] = "Science de l'ingénieur";
          $secteur[] = "Mécanique";

          for($k=0 ; $k < $nbFormations ; $k++){
            //Déclaration
              $formation = new Formation();

            //Initialisation
              $formation->setNomCourt($diplome[$faker->numberBetween($min = 0, $max = 4)] . ' ' . $secteur[$faker->numberBetween($min = 0, $max = 6)]);
              $formation->setNomLong('La formation requise pour ce stage est la suivante: ' . $formation->getNomCourt());

            //Rentrer la formation dans un tableau
              $tabFormation[] = $formation;

            //On persiste l'objet
              $manager->persist($formation);

          }

          //Envoi en BD
              $manager->flush();



      //---------------ENTREPRISES---------------//
        //Variable
          $nbEntreprises = 10;
          $nbStages = 3;

          $secActivité[] = "Technologique";
          $secActivité[] = "Informatique";
          $secActivité[] = "Télécommunication";
          $secActivité[] = "Automobile";
          $secActivité[] = "Spectacle";
          $secActivité[] = "Industriel";
          $secActivité[] = "Agroalimentaire";
          $secActivité[] = "Réseaux";
          $secActivité[] = "Commerce";
          $secActivité[] = "Marketing";

          for($i=0 ; $i < $nbEntreprises ; $i++){
            //Déclaration
              $entreprise = new Entreprise();

            //Initialisation ENTREPRISES
              $entreprise->setNom($faker->company());
              $entreprise->setActivite($secActivité[$faker->randomDigit()]);
              $entreprise->setRue($faker->streetName());
              $entreprise->setNumRue($faker->randomDigitNotNull());
              $entreprise->setCodePostale($faker->regexify("[1-9][0-9][0-9][0-9][0-9]"));
              $entreprise->setVille($faker->city());
              $entreprise->setPays($faker->country());
              $entreprise->setComplementAdresse($faker->randomDigitNotNull());
              $entreprise->setUrl($faker->url());

            //Initialisation de 2 Stages
              for($j=0 ; $j < $nbStages ; $j++){
                //Déclaration STAGES
                  $stages = new Stage();
                  $numDeLaFormation = $faker->randomDigit(); //C'est la valeur tirrer au hazad pour choisir une formation dans le tableau des formations
                  $nbFormation = $faker->numberBetween($min = 0, $max = 1); //Variable = 1 si il y a une formation dans se stage

                //Initialisation
                  $stages->setTitre($faker->jobTitle());
                  $stages->setSousTitre('Stage chez ' . $entreprise->getNom());
                  $stages->setDescriptionCourte('Stage de ' . $stages->getTitre() . ' dans la compagnie ' . $entreprise->getNom());

                  if($nbFormation == 1){
                    $stages->setDescriptionLongue('Stage de ' . $stages->getTitre() . ' à '. $entreprise->getVille() . ' (' . $entreprise->getPays() . '), dans la fameuse compagnie de ' . $entreprise->getActivite() . ', ' . $entreprise->getNom() . '. ' . $tabFormation[$numDeLaFormation]->getNomLong());
                  }
                  else {
                    $stages->setDescriptionLongue('Stage de ' . $stages->getTitre() . ' à '. $entreprise->getVille() . ' (' . $entreprise->getPays() . '), dans la fameuse compagnie de ' . $entreprise->getActivite() . ', ' . $entreprise->getNom() . '.');
                  }
                  $stages->setEmail($faker->email());

                //Mis en place des liens
                  //lien avec entreprises
                    $stages->setEntreprise($entreprise);
                    $entreprise->addStage($stages);

                  //lien avec formation
                    $stages->addFormation($tabFormation[$numDeLaFormation]);

                //On persiste l'objet
                  $manager->persist($stages);
              }

            //On persiste l'objet entreprise
              $manager->persist($entreprise);

          }

          //Envoi en BD
              $manager->flush();

    }
}
