<?php
namespace App\Controllers;
class Competences extends BaseController
{
    public function index()
    {
        return view('pages/competences');
    }

    public function comp1()
    {
        return view('pages/comp1');
    }

    public function comp2()
    {
        return view('pages/comp2');
    }

    public function comp6()
    {
        return view('pages/comp6');
    }
}
