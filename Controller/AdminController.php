<?php
namespace App\Controller;

use App\Core\SuccessError;
use App\Core\Util;
use App\Core\Validate;
use App\Models\AnnoncesModel;

class AdminController extends AbstractController
{
    public function index()
    {
        // On vérifie si on est admin sinon redirigé
        if(Validate::isConnected() && Validate::isAdmin())
        {
            $this->render('admin/index', [], 'admin');
        }else
        {
            SuccessError::redirect(['erreur' => 'Vous n\'êtes pas administrateur'], Util::getRefererOrRacine());
        }
    }

    /**
     * Affiche la liste des annonces sous forme de tableau
     *
     * @return void
     */
    public function annonces()
    {
        if(Validate::isConnected() && Validate::isAdmin())
        {
            $annonceModel = new AnnoncesModel;
            $annonces = $annonceModel->findAll();
            foreach($annonces as $annonce){
                $desc = $annonce->description;
                if(strlen($desc) > 50)
                {
                    $annonce->description = substr($desc, 0, -(strlen($desc) - 50));
                }
            }
            $this->render('admin/annonces', compact('annonces'), 'admin');
        }else
        {
            SuccessError::redirect(['erreur' => 'Vous n\'êtes pas administrateur'], Util::getRefererOrRacine());
        }
    }

    /**
     * Active ou désactive une annonce
     *
     * @param mixed $id
     * @return void
     */
    public function activeAnnonce($id) : void
    {
        if(Validate::isConnected() && Validate::isAdmin())
        {
            $id = (int)$id;
            $annonceModel = new AnnoncesModel;
            $annonce = $annonceModel->find($id);
            if($annonce){
                $annonce = $annonceModel->hydrate($annonce);
                $annonce->setActif($annonce->getActif() ? 0 : 1);
                $annonce->update();
            } 
        }else
        {
            SuccessError::redirect(['erreur' => 'Vous n\'êtes pas administrateur'], Util::getRefererOrRacine());
        }
    }

}