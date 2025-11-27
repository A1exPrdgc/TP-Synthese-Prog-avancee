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

// Mot de passe oublié
$routes->get('forgot-password', 'Auth::forgotPassword');
$routes->post('forgot-password', 'Auth::doForgotPassword');

$routes->get('reset-password/(:segment)', 'Auth::resetPassword/$1');
$routes->post('reset-password/(:segment)', 'Auth::doResetPassword/$1');

$routes->get('testMail', 'Debug::testMailConfig');
$routes->post('testMail', 'Debug::testMailConfig');

// DS Ajout
$routes->get('DS/Ajout', 'DS\Ajout::index');
$routes->post('DS/Ajout/save', 'DS\Ajout::save');

$routes->get('DS/Detail', 'DS\Detail::view');

// Rattrapage Ajout
$routes->get('Rattrapage/Ajout', 'Rattrapage::ajout');
$routes->post('Rattrapage/Ajout/save', 'Rattrapage::save');
