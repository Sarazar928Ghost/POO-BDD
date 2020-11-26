<?php
namespace App\Controller;

use App\Models\AnnoncesModel;

class AdminController extends AbstractController
{
    public function index()
    {
        // On vérifie si on est admin sinon redirigé
        $this->isAdmin();
        $this->render('admin/index', [], 'admin');
    }

    /**
     * Affiche la liste des annonces sous forme de tableau
     *
     * @return void
     */
    public function annonces()
    {
        $this->isAdmin();
        $annonceModel = new AnnoncesModel;
        $annonces = $annonceModel->findAll();
        $this->render('admin/annonces', compact('annonces'), 'admin');
    }

    /**
     * Permet de supprimer une annonce via le panel admin
     *
     * @param mixed $id
     * @return void
     */
    public function deleteAnnonce($id)
    {
        $this->isAdmin();
        $id = (int)$id;
        $annonce = new AnnoncesModel;
        $annonce->delete($id);
        header('Location: '. $_SERVER['HTTP_REFERER']);
    }

    /**
     * Active ou désactive une annonce
     *
     * @param mixed $id
     * @return void
     */
    public function activeAnnonce($id) : void
    {
        $this->isAdmin();
        $id = (int)$id;
        $annonceModel = new AnnoncesModel;
        $annonce = $annonceModel->find($id);
        if($annonce){
            $annonce = $annonceModel->hydrate($annonce);
            $annonce->setActif($annonce->getActif() ? 0 : 1);
            $annonce->update();
        } 
    }

    /**
     * Vérifie si on est admin
     *
     * @return void
     */
    private function isAdmin() : void
    {
        // On vérifie si on est connecté et si "ROLE_ADMIN" est dans nos roles
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])){
            // On est connecté
            if(in_array('ROLE_ADMIN', $_SESSION['user']['roles']))
            {
                // On est admin
                return;
            }
        }
        $_SESSION['erreur'] = 'Vous n\'avez pas accès a cette zone';
        header('Location: /');
        exit;
    }
}