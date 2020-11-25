<?php
namespace App\Controller;

use App\Autoloader;
use App\Models\AnnoncesModel;

class MainController extends AbstractController
{
    public function index()
    {
      $this->render('main/index', [], 'home');
    }
}