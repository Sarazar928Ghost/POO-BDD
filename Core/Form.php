<?php
namespace App\Core;

class Form
{
    private $formCode = '';

    

    /**
     * Genère le formulaire HTML
     */ 
    public function create()
    {
        return $this->formCode;
    }
    
    /**
     * Valide si tous les champs proposés sont remplis
     *
     * @param  array $form Tableau issu du formulaire ($_POST, $_GET)
     * @param  array $champs Tableau listant les champs obligatoires
     * @return bool
     */
    public static function validate(array $form, array $champs) : bool
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
    
    /**
     * Ajoute les attributs envoyé à la balise
     *
     * @param  array $attributs Tableau associatif ['class' => 'form-control, 'required' => true]
     * @return string Chaine de caractères générée
     */
    private function addAttributs(array $attributs) : string
    {
        // On initialise une chaîne de caractères
        $str = '';

        // On liste les attributs "courts"
        $courts = ['checked', 'disabled', 'readonly', 'multiple', 'required', 'autofocus', 'novalidate', 'formnovalidate'];

        // On boucle sur le tableau d'attributs
        foreach($attributs as $attribut => $valeur)
        {
            // Si l'attribut est dans la liste des attributs courts
            if(in_array($attribut, $courts) && $valeur == true)
            {
                $str .= " {$attribut}";
            }else
            {
                // On ajoute attribut='valeur'
                $str .= " {$attribut}=\"{$valeur}\"";
            }
        }

        return $str;
    }
    
    /**
     * Balise d'ouverture du formulaire
     *
     * @param  string $method Méthode du formulaire (post ou get)
     * @param  string $action Action du formulaire
     * @param  array $attributs Attributs
     * @return self
     */
    public function debutForm(string $method = 'post', string $action = '#', array $attributs = []) : self
    {
        // On crée la balise form
        $this->formCode .= "<form action='{$action}' method='{$method}'";

        // On ajoute les attributs éventuels
        $this->formCode .= $attributs ? $this->addAttributs($attributs).'>' : '>';

        return $this;
    }
    
    /**
     * Balise de fermeture du formulaire
     *
     * @return self
     */
    public function finForm() : self
    {
        $this->formCode .= '</form>';
        return $this;
    }
    
    /**
     * Création de <label>
     *
     * @param  string $for
     * @param  string $texte
     * @param  array $attributs
     * @return self
     */
    public function ajoutLabelFor(string $for, string $texte, array $attributs = []) : self
    {
        // On ouvre la balise
        $this->formCode .= "<label for='{$for}'";

        // On ajoute les attributs
        $this->formCode .= $attributs ? $this->addAttributs($attributs).'>' : '>';

        // On ajoute le texte
        $this->formCode .= "{$texte}</label>";

        return $this;
    }
    
    /**
     * ajoutInput
     *
     * @param  mixed $type
     * @param  mixed $nom
     * @param  mixed $attributs
     * @return self
     */
    public function ajoutInput(string $type, string $nom, array $attributs = []) : self
    {
       // On ouvre la balise 
       $this->formCode .= "<input type='{$type}' name='{$nom}'";

       // On ajoute les attributs
       $this->formCode .= $attributs ? $this->addAttributs($attributs).'>' : '>';
        
       return $this;
    }
    
    /**
     * ajoutTextarea
     *
     * @param  string $nom
     * @param  string $valeur
     * @param  array $attributs
     * @return self
     */
    public function ajoutTextarea(string $nom, string $valeur = '', array $attributs = []) : self
    {
       // On ouvre la balise 
       $this->formCode .= "<textarea name='{$nom}'";

       // On ajoute les attributs
       $this->formCode .= $attributs ? $this->addAttributs($attributs).'>' : '>';

       // On ajoute le texte
       $this->formCode .= "{$valeur}</textarea>";
        
       return $this;
    }
    
    /**
     * ajoutSelect
     *
     * @param  string $nom
     * @param  array $options
     * @param  array $attributs
     * @return self
     */
    public function ajoutSelect(string $nom, array $options, array $attributs = []) : self
    {
        // On crée le select
        $this->formCode .= "<select name='{$nom}'";

        // On ajoute les attributs
        $this->formCode .= $attributs ? $this->addAttributs($attributs).'>' : '>';

        // On ajoute les options
        foreach($options as $valeur => $texte)
        {
            $this->formCode .= "<option value=\"{$valeur}\">{$texte}</option>";
        }

        // On ferme le select
        $this->formCode .= '</select>';

        return $this;
    }
    
    /**
     * ajoutBouton
     *
     * @param  string $texte
     * @param  array $attributs
     * @return self
     */
    public function ajoutBouton(string $texte, array $attributs = []) : self
    {
        // On ouvre le boutton
        $this->formCode .= '<button ';

        // On ajoute les attributs
        $this->formCode .= $attributs ? $this->addAttributs($attributs).'>' : '>';

        // On ajoute le texte et on ferme
        $this->formCode .= "{$texte}</button>";

        return $this;
    }
}