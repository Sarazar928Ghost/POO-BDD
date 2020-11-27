<?php
namespace App\Controller;

use App\Models\AnnoncesModel;
use App\Core\Form;
use App\Core\SuccessError;
use App\Core\Util;
use App\Core\Validate;

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
     * @param  mixed $id Id de l'annonce
     * @return void
     */
    public function read($id)
    {
        $id = (int)$id;
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

    /**
     * Ajouter une annonce
     *
     * @return void
     */
    public function ajouter()
    {
        // On vérifie si l'utilisateur est connecté
        if(Validate::isConnected())
        {
            $erreur = '';
            // L'utilisateur est connecté

            //On vérifie si le formulaire est complet
            if(Validate::validateForm($_POST, ['titre', 'description']))
            {
                // Le formulaire est complet
                // On se protège contre les failles XSS
                // strip_tags , htmlentities, htmlspecialchars
                $titre = strip_tags($_POST['titre']);
                $description = strip_tags($_POST['description']);

                if(strlen($titre) < 26)
                {
                    // On instancie notre modèle
                    $annonce = new AnnoncesModel;

                    // On hydrate
                    $annonce->setTitre($titre)
                        ->setDescription($description)
                        ->setActif(1)
                        ->setUsers_id($_SESSION['user']['id']);

                    // On enregistre
                    $annonce->create();

                    // On redirige
                    Util::redirect('/users/profil', ['message' => SUCESS_AJOUT_ANNONCE]);
                }

                $erreur = 'Le titre est trop long ( max : 25 )';
            }else
            {
                $erreur = 'Le formulaire est incomplet';
            }

            if($erreur){
                // Le formulaire est incomplet
                $_SESSION['erreur'] = !empty($_POST) ? $_SESSION['erreur'] = $erreur : '';
                $titre = isset($_POST['titre']) ? strip_tags($_POST['titre']) : '';
                $description = isset($_POST['description']) ? strip_tags($_POST['description']) : '';
            }



            $form = new Form;
            $form->debutForm()
                ->ajoutLabelFor('titre', 'Titre de l\'annonce :')
                ->ajoutInput('text', 'titre', ['id' => 'titre', 'class' => 'form-control', 'value' => $titre])
                ->ajoutLabelFor('description', 'Description de l\'annonce :')
                ->ajoutTextarea('description', $description, ['id' => 'description', 'class' => 'form-control'])
                ->ajoutBouton('Ajouter', ['class' => 'btn btn-primary'])
                ->finForm();

            $this->render('annonces/ajouter', ['form' => $form->create()]);
        }else
        {
            // L'utilisateur n'est pas connecté
            Util::redirect('/users/login', ['erreur' => NOT_CONNECTED]);
        }
    }
    /**
     * Modifier une annonce
     *
     * @param mixed $id
     * @return void
     */
    public function modifier($id)
    {
        $id = (int)$id;
        // On vérifie si l'utilisateur est connecté
        if(Validate::isConnected())
        {
            $erreur = '';
            // On va vérifier si l'annonce existe dans la base
            // On instancie notre modèle
            $annonceModel = new AnnoncesModel;

            // On cherche l'annonce avec l'id $id
            $annonce = $annonceModel->find($id);

            // Si l'annonce n'éxiste pas, on retourne à la liste des annonces
            if(!$annonce)
            {
                Util::redirect(Util::getRefererOrRacine(), ['erreur' => NOT_EXIST_ANNONCE]);
            }

            // On vérifie si l'utilisateur est propriétaire de l'annonce
            if(!Validate::isAdmin() && $annonce->users_id !== $_SESSION['user']['id'])
            {
                Util::redirect('/users/profil', ['erreur' => NOT_ACCESS_ANNONCE]);
            }

            //On traite le formulaire
            if(Validate::validateForm($_POST, ['titre', 'description']))
            {
                // On se protège contre les failles XSS
                $titre = strip_tags($_POST['titre']);
                $description = strip_tags($_POST['description']);

                if(strlen($titre) < 26)
                {
                    // On stocke l'annonce
                    $annonceModif = new AnnoncesModel;

                    // On hydrate
                    $annonceModif->setId($annonce->id)
                        ->setTitre($titre)
                        ->setDescription($description);

                    // On met à jour l'annonce
                    $annonceModif->update();

                    // On redirige
                    Util::redirect('/users/profil', ['message' => SUCESS_MODIF_ANNONCE]);
                }

                $erreur = 'Le titre est trop long ( max : 25 )';
            }else
            {
                $erreur = 'Le formulaire est incomplet';
            }

            if($erreur){
                // Le formulaire est incomplet
                $_SESSION['erreur'] = !empty($_POST) ? $_SESSION['erreur'] = $erreur : '';
                $titre = isset($_POST['titre']) && !empty($_POST['titre']) ? strip_tags($_POST['titre']) : $annonce->titre;
                $description = isset($_POST['description']) && !empty($_POST['description']) ? strip_tags($_POST['description']) : $annonce->description;
            }

            $form = new Form;
            $form->debutForm()
                ->ajoutLabelFor('titre', 'Titre de l\'annonce :')
                ->ajoutInput('text', 'titre', ['id' => 'titre', 'class' => 'form-control', 'value' => $titre])
                ->ajoutLabelFor('description', 'Description de l\'annonce :')
                ->ajoutTextarea('description', $description, ['id' => 'description', 'class' => 'form-control'])
                ->ajoutBouton('Modifier', ['class' => 'btn btn-primary'])
                ->finForm();

            $this->render('annonces/modifier', ['form' => $form->create()]);

        }else
        {
            // L'utilisateur n'est pas connecté
            Util::redirect('/users/login', ['erreur' => NOT_CONNECTED]);
        }
    }
}