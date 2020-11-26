<?php
namespace App\Core;

abstract class Validate
{
    /**
     * Vérifie si on est admin
     *
     * @return bool
     */
    public static function isAdmin() : bool
    {
        // On vérifie si "ROLE_ADMIN" est dans nos roles
        if(in_array('ROLE_ADMIN', $_SESSION['user']['roles']))
        {
            // On est admin
            return true;
        }
        
        return false;
    }

    /**
     * Vérifie si on est bien connecté
     *
     * @return boolean
     */
    public static function isConnected() : bool
    {
        if(isset($_SESSION['user']) && !empty($_SESSION['user']['id']))
        {
            return true;
        }
        return false;
    }

        
    /**
     * Valide si tous les champs proposés sont remplis
     *
     * @param  array $form Tableau issu du formulaire ($_POST, $_GET)
     * @param  array $champs Tableau listant les champs obligatoires
     * @return bool
     */
    public static function validateForm(array $form, array $champs) : bool
    {
        // On parcourt les champs
        foreach($champs as $champ)
        {
            // Si le champ est absent ou vide dans le formulaire
            if(!isset($form[$champ]) || empty($form[$champ]))
            {
                // On sort en retournant false
                return false;
            }
        }
        return true;
    }
}