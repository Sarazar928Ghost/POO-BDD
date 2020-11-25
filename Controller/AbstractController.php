<?php
namespace App\Controller;

abstract class AbstractController
{
    
    /**
     * Permet de diriger sur une vue
     *
     * @param  string $file
     * @param  array $data
     * @param  string $template
     * @return void
     */
    public function render(string $file, array $data = [], string $template = 'default') : void
    {
        // on extrait le contenu de $data
        extract($data);

        // On démarre le buffer de sortie
        ob_start();
        // A partir de ce point toute sortie est conservée en mémoire

        // On crée le chemin vers la vue
        require_once ROOT . '/Views/' . $file .'.php';

        // Transfère le buffer dans contenu
        $contenu = ob_get_clean();

        // Template de page
        require_once ROOT . '/Views/' . $template . '.php';
    }

}