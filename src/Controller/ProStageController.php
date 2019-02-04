<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Stage;
use App\Entity\Entreprise;
use App\Entity\Formation;

class ProStageController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function accueil(){
        return $this->render('pro_stage/index.html.twig');
    }



    /**
     * @Route("/stages", name="stages")
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
      * @Route("/entreprises", name="entreprises")
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
       * @Route("/formations", name="formationS")
       */
       public function afficherFormations(){
         //Récupérer le répository de formation
           $repositoryFormation = $this->getDoctrine()->getRepository(Formation::class);

         //Récupérer les données du répository
           $formations = $repositoryFormation->findAll();

         //Envoyer les données à la vue
           return $this->render('pro_stage/formation.html.twig',['formations' => $formations]);
       }



       /**
        * @Route("/entreprise-{id}", name="entreprise")
        */
        public function afficherStagesEntreprise($id){
          //Récupérer les répository
            $repositoryStage = $this->getDoctrine()->getRepository(Stage::class);
            $repositoryEntreprise = $this->getDoctrine()->getRepository(Entreprise::class);

          //Récupérer les données de la BD
            $stages = $repositoryStage->findByEntreprise($id);
            $entreprise = $repositoryEntreprise->findOneBy(['id' => $id]);

          //Envoyer les données à la vue
            return $this->render('pro_stage\stagesParEntreprise.html.twig',['entreprise'=> $entreprise ,'stages' => $stages]);
        }



         /**
          * @Route("/formation-{id}", name="formation")
          */
          public function afficherStagesFormation($id){
            //Récupérer le répository
              $repositoryFormation = $this->getDoctrine()->getRepository(Formation::class);

            //Récupérer les données de la BD
              $formation = $repositoryFormation->findOneBy(['id' => $id]);
              //Récupérer les stages de la formation
                $stages = $formation->getStages();

            //Envoyer les données à la vue
              return $this->render('pro_stage\stagesParFormation.html.twig',['formation'=> $formation ,'stages' => $stages]);
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