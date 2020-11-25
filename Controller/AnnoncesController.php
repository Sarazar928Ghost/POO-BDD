<?php
namespace App\Controller;

use App\Models\AnnoncesModel;

class AnnoncesController extends AbstractController
{  
    /**
     * Cette méthode affichera une page listant toutes les annonces de la base de données
     *
     * @return void
     */
    public function index() : void
    {
        // On instancie le modèle correspondant à la table 'annonces'
        $annoncesModel = new AnnoncesModel;

        // On va chercher toutes les annonces
        $annonces = $annoncesModel->findBy(['actif' => 1]);

        $this->render('annonces/index', compact('annonces'));
    }
    
    /**
     * Affiche 1 annonce
     *
     * @param  int $id Id de l'annonce
     * @return void
     */
    public function read(int $id)
    {
        // On instancie le modèle
        $annoncesModel = new AnnoncesModel;

        // On va chercher l'annonce
        $annonce = $annoncesModel->find($id);

        // On envoie à la vue
        if($annonce){
            (!empty($annonce->actif)) ? $this->render('annonces/read', compact('annonce')) : $this->index();
        }else{
            $this->index();
        }
    }
}