<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Stage;
use App\Entity\Entreprise;
use App\Entity\Formation;
use Doctrine\Common\Persistence\ObjectManager;
use App\Form\EntrepriseType;
use App\Form\StageType;

class ProStageController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function accueil(){
        return $this->render('pro_stage/index.html.twig');
    }


    /**
     * @Route("/entreprise/ajouter", name="ajoutEntreprise")
     */
    public function ajouterEntreprise(Request $request, ObjectManager $manager){
      //Création d'une entreprise vide qui sera rempli par le Formulaire
        $entreprise = new Entreprise();

      //Création du Formulaire permettant de saisir une ressource
      $formulaireEntreprise = $this->createForm(EntrepriseType::class, $entreprise);


      //Analyse la derniére requete html pour voir si le tableau post
      // contient les variables qui ont été rentrées, si c'est le cas
      // alors il hydrate l'objet entreprise
        $formulaireEntreprise->handleRequest($request);

        //dump($entreprise);
      //Vérifier que le formulaire a été soumis
        if($formulaireEntreprise->isSubmitted()){
            //Envoyer les donnée en BD
              $manager->persist($entreprise);
              $manager->flush();

            //Redirection
              return $this->redirectToRoute('lesEntreprises');
        }

      //Générer la représentation graphique du formulaire
        $vueFormulaire = $formulaireEntreprise->createView();

      //Envoyer la page à la vue
        return $this->render('pro_stage/ajoutModifEntreprise.html.twig',["formulaire" => $vueFormulaire,"action" => "ajout"]);
    }


    /**
     * @Route("/entreprise-{id}/modifier", name="modifEntreprise")
     */
    public function modifierEntreprise(Request $request, ObjectManager $manager, Entreprise $entreprise){
      //Création du Formulaire permettant de modifier une ressource
        $formulaireEntreprise = $this->createForm(EntrepriseType::class, $entreprise);

      //Analyse la derniére requete html pour voir si le tableau post
      // contient les variables qui ont été rentrées, si c'est le cas
      // alors il hydrate l'objet entreprise
        $formulaireEntreprise->handleRequest($request);

        //dump($entreprise);
      //Vérifier que le formulaire a été soumis
        if($formulaireEntreprise->isSubmitted()){
            //Envoyer les donnée en BD
              $manager->persist($entreprise);
              $manager->flush();

            //Redirection
              return $this->redirectToRoute('lesEntreprises');
        }

      //Générer la représentation graphique du formulaire
        $vueFormulaire = $formulaireEntreprise->createView();

      //Envoyer la page à la vue
        return $this->render('pro_stage/ajoutModifEntreprise.html.twig',["formulaire" => $vueFormulaire,"action" => "modif"]);
    }


    /**
     * @Route("/stage/ajouter", name="ajoutStage")
     */
    public function ajouterStage(Request $request, ObjectManager $manager){
      //Création d'un stage vide qui doit être hydraté
          $stage = new Stage();

      //Création du formulaire ajout de stage
          $formulaireStage = $this->createForm(StageType::class,$stage);

      //Récupérer les données
          $formulaireStage->handleRequest($request);

      //Vérifier que les données ont été soumis
          if($formulaireStage->isSubmitted() && $formulaireStage->isValid()){
            //Envoyer les données en BD
                $manager->persist($stage);
                $manager->flush();

            //Rediriger vers la page des stages
                return $this->redirectToRoute('lesStages');
          }

      //Générer la représentation graphique du formulaire
          $vueFormulaire = $formulaireStage->createView();

      //Envoyer la représentation graphique du formulaire à la vu
          return $this->render('pro_stage/ajoutStage.html.twig',['formulaire' => $vueFormulaire]);
    }



    /**
     * @Route("/stages", name="lesStages")
     */
     public function afficherStages(){
      //Récupérer le répository de stage
        $repositoryStage = $this->getDoctrine()->getRepository(Stage::class);

      //Récupérer les données du répository
        $stages = $repositoryStage->findAllByAlphabeticOrderDQL();

      //Envoyer les données à la vue
        return $this->render('pro_stage\afficherLesStages.html.twig',['stages' => $stages]);
     }



     /**
      * @Route("/entreprises", name="lesEntreprises")
      */
      public function afficherEntreprises(){
        //Récupérer répository d'entreprises
          $repositoryEntreprise = $this->getDoctrine()->getRepository(Entreprise::class);

        //Récupérer les données du répository
          $entreprises = $repositoryEntreprise->findAll();

        //Envoyer les données sur la vue
          return $this->render('pro_stage/entreprise.html.twig',['entreprises' => $entreprises]);

      }



      /**
       * @Route("/formations", name="lesFormations")
       */
       public function afficherFormations(){
         //Récupérer le répository de formation
           $repositoryFormation = $this->getDoctrine()->getRepository(Formation::class);

         //Récupérer les données du répository
           $formations = $repositoryFormation->findAll();

         //Envoyer les données à la vue
           return $this->render('pro_stage/formation.html.twig',['formations' => $formations]);
       }


//Ancien controleur pour afficher les stages d'une entreprise donnée

//       /**
//        * @Route("/entreprise-{id}", name="entreprise")
//        */
/*        public function afficherStagesEntreprise($id){
          //Récupérer les répository
            $repositoryStage = $this->getDoctrine()->getRepository(Stage::class);
            $repositoryEntreprise = $this->getDoctrine()->getRepository(Entreprise::class);

          //Récupérer les données de la BD
            $stages = $repositoryStage->findByEntreprise($id);
            $entreprise = $repositoryEntreprise->findOneBy(['id' => $id]);

          //Envoyer les données à la vue
            return $this->render('pro_stage\stagesParEntreprise.html.twig',['entreprise'=> $entreprise ,'stages' => $stages]);
        }*/


//Nouveau controleur pour afficher les stages d'une entreprise donnée

        /**
         * @Route("/entreprise-{nom}", name="entreprise")
         */
               public function afficherStagesEntreprise($nom){
                 //Récupérer les répository
                   $repositoryStage = $this->getDoctrine()->getRepository(Stage::class);

                 //Récupérer les données de la BD
                   $stages = $repositoryStage->findStagesByEntreprise($nom);

                 //Envoyer les données à la vue
                   return $this->render('pro_stage\stagesParEntreprise.html.twig',['nomEntreprise'=> $nom ,'stages' => $stages]);
               }



//         /**
//          * @Route("/formation-{id}", name="formation")
//          */
/*          public function afficherStagesFormation($id){
            //Récupérer le répository
              $repositoryFormation = $this->getDoctrine()->getRepository(Formation::class);

            //Récupérer les données de la BD
              $formation = $repositoryFormation->findOneBy(['id' => $id]);
              //Récupérer les stages de la formation
                $stages = $formation->getStages();

            //Envoyer les données à la vue
              return $this->render('pro_stage\stagesParFormation.html.twig',['formation'=> $formation ,'stages' => $stages]);
          }*/



          /**
           * @Route("/formation-{nom}", name="formation")
           */
           public function afficherStagesFormation($nom){
             //Récupérer le répository
               $repositoryStage = $this->getDoctrine()->getRepository(Stage::class);

             //Récupérer les données de la BD
               $stages = $repositoryStage->findStagesByFormation($nom);

             //Envoyer les données à la vue
               return $this->render('pro_stage\stagesParFormation.html.twig',['nomFormation'=> $nom ,'stages' => $stages]);
           }



          /**
           * @Route("/stage-{id}", name="stage")
           */
           public function afficherStage($id){
             //Récupérer répository du stage
                $repositoryStage = $this->getDoctrine()->getRepository(Stage::class);

             //Récupérer le données du répository
                $stage = $repositoryStage->findOneBy(['id' => $id]);

             //Récupérer les formations du stage
                $formations = $stage->getFormations();

            //Envoyer les données à la vue
                return $this->render('pro_stage\afficherLeStage.html.twig',['stage' => $stage, 'formations' => $formations]);

           }

}
