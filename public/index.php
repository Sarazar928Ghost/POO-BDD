<?php
use App\Autoloader;
use App\Core\Main;

// On définit une constante contenant le dossier racine du projet
define('ROOT', dirname(__DIR__));
define('NOT_CONNECTED', 'Vous devez être connecté pour accéder a cette page');
define('SUCESS_AJOUT_ANNONCE', 'Votre annonce a été enregistrée avec succès');
define('SUCESS_MODIF_ANNONCE', 'Votre annonce a bien été modifié avec succès');
define('NOT_EXIST_ANNONCE', 'L\'annonce recherchée n\'éxiste pas');
define('NOT_ACCESS_ANNONCE', 'Vous n\'avez pas accès à cette page');
define('NOT_ADMINISTRATOR', 'Vous n\'êtes pas administrateur');
define('ALREADY_CONNECTED', 'Vous êtes déjà connecté');
define('LOGIN_INCORRECT', 'L\'adresse e-mail et/ou le mot de passe est incorrect');
define('EMAIL_ALREADY_USE', 'L\'email est déjà utilisé');
define('SUCCESS_REGISTER', 'Vous vous êtes bien inscris !');
define('SUCCESS_DELETE_ANNONCE', 'L\'annonce a bien été supprimé');
define('NOT_UR_ANNONCE', 'L\'annonce demandé ne vous appartient pas');

// On importe l'autoloader
require_once ROOT.'/Autoloader.php';
Autoloader::register();

// On instancie Main
$app = new Main;

// On démarre l'application
$app->start();
