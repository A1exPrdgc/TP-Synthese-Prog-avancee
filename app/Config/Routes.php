<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Auth
$routes->get('connecter', 'Auth::login');
$routes->post('connecter', 'Auth::doLogin');

$routes->get('inscription', 'Auth::signin');
$routes->post('inscription', 'Auth::doSignin');

$routes->get('deconnecter', 'Auth::logout');

// Profile
$routes->get('profil', 'Profil::index');
$routes->post('profil', 'Profil::update');

$routes->get('profil/modifier', 'Profil::edit');
$routes->post('profil/mettre-a-jour', 'Profil::update');

$routes->get('profil/modifier-mot-de-passe', 'Profil::change_password');
$routes->post('profil/sauvegarder', 'Profil::save_password');

// Mot de passe oublié
$routes->get('mot-de-passe-oublie', 'Auth::forgotPassword');
$routes->post('mot-de-passe-oublie', 'Auth::doForgotPassword');

$routes->get('reinitialiser/(:segment)', 'Auth::resetPassword/$1');
$routes->post('reinitialiser/(:segment)', 'Auth::doResetPassword/$1');

// DS
$routes->get('ds', 'DS::index');
$routes->get('ds/ajouter', 'DS::ajout');
$routes->post('ds/sauvegarder', 'DS::save');
$routes->get('ds/detail/(:num)', 'DS::detail/$1');
$routes->get('ds/valider/(:num)', 'DS::validerRattrapage/$1');
$routes->get('ds/refuser/(:num)', 'DS::refuserRattrapage/$1');
$routes->get('DS/getResourcesBySemester', 'DS::getResourcesBySemester');
$routes->get('DS/getTeachersByResource', 'DS::getTeachersByResource');
$routes->get('DS/getStudentsBySemester', 'DS::getStudentsBySemester');


// Rattrapage
$routes->get('rattrapage', 'Rattrapage::index');
$routes->get('rattrapage/ajouter/(:num)', 'Rattrapage::ajout/$1');
$routes->post('rattrapage/sauvegarder/(:num)', 'Rattrapage::save/$1');
$routes->get('rattrapage/detail/(:num)', 'Rattrapage::detail/$1');
$routes->get('rattrapage/modifier/(:num)', 'Rattrapage::modifier/$1');
$routes->post('rattrapage/modifier/(:num)', 'Rattrapage::update/$1');
$routes->get('rattrapage/refuser/(:num)', 'Rattrapage::refuser/$1');

