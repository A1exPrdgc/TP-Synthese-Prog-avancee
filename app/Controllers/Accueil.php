<?php
namespace App\Controllers;
class Accueil extends BaseController
{
public function index()
{
 return view('pages/accueil');
}
}