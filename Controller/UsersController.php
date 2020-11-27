<?php
namespace App\Controller;

use App\Core\Form;
use App\Core\SuccessError;
use App\Core\Util;
use App\Core\Validate;
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
        if(Validate::isConnected()){
            Util::redirect(Util::getRefererOrRacine(), ['erreur' => ALREADY_CONNECTED]);
        }
        // On vérifie si le formulaire est complet
        if(Validate::validateForm($_POST, ['email', 'password']))
        {
            // Le formulaire est complet
            // On va chercher dans la base de données l'utilisateur avec l'email entré
            $user = false;
            $email = strip_tags($_POST['email']);
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                $usersModel = new UsersModel;
                $user = $usersModel->findOneByEmail($email);
            }else{
                Util::redirect('/users/login', ['erreur' => LOGIN_INCORRECT]);
            }

            // Si l'utlistauer n'éxiste pas
            if(!$user){
                // On evnoie un message de session
                Util::redirect('/users/login', ['erreur' => LOGIN_INCORRECT]);
            }
            // L'utilisateur éxiste
            $user = $usersModel->hydrate($user);

            // On vérifie si le mot de passe est correct
            if(password_verify($_POST['password'], $user->getPassword())){
                // Le mot de passe est bon
                // On crée la session
                $user->setSession();
                Util::redirect('/');
            }else
            {
                Util::redirect('/users/login', ['erreur' => LOGIN_INCORRECT]);
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
        if(Validate::isConnected()){
            Util::redirect(Util::getRefererOrRacine(), ['erreur' => ALREADY_CONNECTED]);
        }
        // On vérifie si le formulaire est valide
        if(Validate::validateForm($_POST, ['email', 'password']))
        {
            // Le formulaire est valide
            // On "nettoie" l'adresse email
            $email = strip_tags($_POST['email']);

            // On chiffre le mot de passe
            $pass = password_hash($_POST['password'], PASSWORD_ARGON2I);
            
            // Vérifie si l'email reçu est un email valide
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                // On hydrate l'utilisateur
                $userModel = new UsersModel;
                
                $user = $userModel->findOneByEmail($email);
                if($user){
                    Util::redirect(Util::getRefererOrRacine(), ['erreur' => EMAIL_ALREADY_USE]);
                }

                $userModel->setEmail($email)
                    ->setPassword($pass);

                // On stocke l'utilisateur en base de données
                $userModel->create();
                Util::redirect('/users/login', ['message' => SUCCESS_REGISTER]);
            }
            Util::redirect('/users/register', ['erreur' => LOGIN_INCORRECT]);
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
        if(!Validate::isConnected()){
            Util::redirect(Util::getRefererOrRacine(), ['erreur' => ALREADY_CONNECTED]);
        }

        $annonceModel = new AnnoncesModel;
        $annonces = $annonceModel->findBy(['users_id' => $_SESSION['user']['id']]);

        foreach($annonces as $annonce){
            $desc = $annonce->description;
            if(strlen($desc) > 50)
            {
                $annonce->description = substr($desc, 0, -(strlen($desc) - 50));
            }
        }

        $this->render('users/profil', compact('annonces'));
    }

    public function deleteAnnonce($id)
    {
        // Si l'utilisateur n'est pas connecté
        if(!Validate::isConnected()){
            Util::redirect(Util::getRefererOrRacine(), ['erreur' => ALREADY_CONNECTED]);
        }

        $annonceModel = new AnnoncesModel;
        $annonce = $annonceModel->find($id);
        if($annonce)
        {
            if($_SESSION['user']['id'] == $annonce->users_id || Validate::isAdmin())
            {
                $annonceModel->delete($id);
                Util::redirect(Util::getRefererOrRacine(), ['message' => SUCCESS_DELETE_ANNONCE]);
            }else
            {
                Util::redirect(Util::getRefererOrRacine(), ['erreur' => NOT_UR_ANNONCE]);
            }
        }else
        {
            Util::redirect(Util::getRefererOrRacine(), ['erreur' => NOT_EXIST_ANNONCE]);
        }
    }
    
    /**
     * Déconnexion de l'utilisateur
     *
     * @return void
     */
    public function logout()
    {
        if(Validate::isConnected()){
            unset($_SESSION['user']);
        }
        Util::redirect('/users/login');
    }
}