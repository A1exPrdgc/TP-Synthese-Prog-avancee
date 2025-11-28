<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Auth
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::doLogin');

$routes->get('signin', 'Auth::signin');
$routes->post('signin', 'Auth::doSignin');

$routes->get('logout', 'Auth::logout');

$routes->get('/', 'Login::index');
$routes->get('login', 'Login::index');

// Profile
$routes->get('profil', 'Profil::index');
$routes->post('profil', 'Profil::update');

$routes->get('profil/edit', 'Profil::edit');
$routes->post('profil/update', 'Profil::update');

// Mot de passe oublié
$routes->get('forgot-password', 'Auth::forgotPassword');
$routes->post('forgot-password', 'Auth::doForgotPassword');

$routes->get('reset-password/(:segment)', 'Auth::resetPassword/$1');
$routes->post('reset-password/(:segment)', 'Auth::doResetPassword/$1');

// DS
$routes->get('DS', 'DS::index');
$routes->get('DS/ajout', 'DS::ajout');
$routes->post('DS/save', 'DS::save');
$routes->get('DS/detail/(:num)', 'DS::detail/$1');
$routes->get('DS/validerRattrapage/(:num)', 'DS::validerRattrapage/$1');
$routes->get('DS/refuserRattrapage/(:num)', 'DS::refuserRattrapage/$1');
$routes->get('DS/getResourcesBySemester', 'DS::getResourcesBySemester');
$routes->get('DS/getTeachersByResource', 'DS::getTeachersByResource');
$routes->get('DS/getStudentsBySemester', 'DS::getStudentsBySemester');

// Test controller
$routes->get('test', 'TestController::index');

// Rattrapage
$routes->get('Rattrapage', 'Rattrapage::index');
$routes->get('Rattrapage/ajout/(:num)', 'Rattrapage::ajout/$1');
$routes->post('Rattrapage/save/(:num)', 'Rattrapage::save/$1');
$routes->get('Rattrapage/detail/(:num)', 'Rattrapage::detail/$1');
$routes->get('Rattrapage/modifier/(:num)', 'Rattrapage::modifier/$1');
$routes->post('Rattrapage/update/(:num)', 'Rattrapage::update/$1');
$routes->get('Rattrapage/refuser/(:num)', 'Rattrapage::refuser/$1');

