<?php
namespace App\Controller;

use App\Core\Form;
use App\Models\AnnoncesModel;
use App\Models\UsersModel;

class UsersController extends AbstractController
{    
    /**
     * Connexion des utilisateurs
     *
     * @return void
     */
    public function login()
    {
        // Si l'utilisateur est déjà connecté on le redirige a l'accueil
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])){
            $_SESSION['erreur'] = 'Vous êtes déjà connecté';
            header('Location: /');
            exit;
        }
        // On vérifie si le formulaire est complet
        if(Form::validate($_POST, ['email', 'password']))
        {
            // Le formulaire est complet
            // On va chercher dans la base de données l'utilisateur avec l'email entré
            $user = false;
            $email = strip_tags($_POST['email']);
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                $usersModel = new UsersModel;
                $user = $usersModel->findOneByEmail($email);
            }else{
                $_SESSION['erreur'] = 'L\'adresse e-mail et/ou le mot de passe est incorrect';
                header('Location: /users/login');
                exit;
            }

            // Si l'utlistauer n'éxiste pas
            if(!$user){
                // On evnoie un message de session
                $_SESSION['erreur'] = 'L\'adresse e-mail et/ou le mot de passe est incorrect';
                header('Location: /users/login');
                exit;
            }
            // L'utilisateur éxiste
            $user = $usersModel->hydrate($user);

            // On vérifie si le mot de passe est correct
            if(password_verify($_POST['password'], $user->getPassword())){
                // Le mot de passe est bon
                // On crée la session
                $user->setSession();
                header('Location: /');
                exit;
            }else
            {
                $_SESSION['erreur'] = 'L\'adresse e-mail et/ou le mot de passe est incorrect';
                header('Location: /users/login');
                exit;
            }
        }

        $form = new Form;

        $form
        ->debutForm()
        ->ajoutLabelFor('email', 'E-mail :')
        ->ajoutInput('email', 'email', ['class' => 'form-control', 'id' => 'email'])
        ->ajoutLabelFor('pass', 'Mot de passe :')
        ->ajoutInput('password', 'password', ['id' => 'pass', 'class' => 'form-control'])
        ->ajoutBouton('Me connecter', ['class' => 'btn btn-primary'])
        ->finForm();

        $this->render('users/login', ['loginForm' => $form->create()]);
    }
    
    /**
     * Inscription des utilistauers
     *
     * @return void
     */
    public function register()
    {
        // Si l'utilisateur est déjà connecté on le redirige a l'accueil
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['id'])){
            $_SESSION['erreur'] = 'Vous êtes déjà connecté';
            header('Location: /');
            exit;
        }
        // On vérifie si le formulaire est valide
        if(Form::validate($_POST, ['email', 'password']))
        {
            // Le formulaire est valide
            // On "nettoie" l'adresse email
            $email = strip_tags($_POST['email']);

            // On chiffre le mot de passe
            $pass = password_hash($_POST['password'], PASSWORD_ARGON2I);
            
            // Vérifie si l'email reçu est un email valide
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                // On hydrate l'utilisateur
                $user = new UsersModel;

                $user->setEmail($email)
                    ->setPassword($pass);

                // On stocke l'utilisateur en base de données
                $user->create();
            }
        }

        $form = new Form;
        $form
        ->debutForm()
        ->ajoutLabelFor('email', 'E-mail :')
        ->ajoutInput('email', 'email', ['id' => 'email', 'class' => 'form-control', 'required' => 'true'])
        ->ajoutLabelFor('pass', 'Mot de passe :')
        ->ajoutInput('password', 'password', ['id' => 'pass', 'class' => 'form-control', 'required' => 'true'])
        ->ajoutBouton('M\'inscrire', ['class' => 'btn btn-primary'])
        ->finForm();

        $this->render('users/register', ['registerForm' => $form->create()]);
    }

    /**
     * Permet d'accéder a son profil
     *
     * @return void
     */
    public function profil() : void
    {
        // Si l'utilisateur n'est pas connecté
        if(!isset($_SESSION['user']) && empty($_SESSION['user']['id'])){
            $_SESSION['erreur'] = 'Vous n\'êtes pas connecté au site';
            header('Location: /');
            exit;
        }

        $annonceModel = new AnnoncesModel;
        $annonces = $annonceModel->findBy(['users_id' => $_SESSION['user']['id']]);

        $this->render('users/profil', compact('annonces'));
    }

    public function deleteAnnonce($id)
    {
        $location = 'Location: ';
        $location .= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
        // Si l'utilisateur n'est pas connecté
        if(!isset($_SESSION['user']) && empty($_SESSION['user']['id'])){
            $_SESSION['erreur'] = 'Vous n\'êtes pas connecté au site';
            header($location);
            exit;
        }

        $annonceModel = new AnnoncesModel;
        $annonce = $annonceModel->find($id);
        if($annonce)
        {
            if($_SESSION['user']['id'] == $annonce->users_id)
            {
                $annonceModel->delete($id);
                $_SESSION['message'] = 'L\'annonce a bien été supprimé';
                header($location);
                exit;
            }else
            {
                $_SESSION['erreur'] = 'L\'annonce demandé ne vous appartient pas';
                header($location);
                exit;
            }
        }else
        {
            $_SESSION['erreur'] = 'L\'annonce demandé n\'éxiste pas';
            header($location);
            exit;
        }
    }
    
    /**
     * Déconexion de l'utilisateur
     *
     * @return void
     */
    public function logout()
    {
        unset($_SESSION['user']);
        header('Location: '. $_SERVER['HTTP_REFERER']);
        exit;
    }
}