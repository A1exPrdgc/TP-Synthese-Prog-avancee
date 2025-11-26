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

// Accueil → liste des rattrapages
$routes->get('/', 'Rattrapage::index');
$routes->get('rattrapages', 'Rattrapage::index');

// Mot de passe oublié
$routes->get('forgot-password', 'Auth::forgotPassword');
$routes->post('forgot-password', 'Auth::sendResetLink');

$routes->get('reset-password/(:segment)', 'Auth::resetPassword/$1');
$routes->post('reset-password/(:segment)', 'Auth::doResetPassword/$1');

$routes->get('testMail', 'Debug::testMailConfig');
$routes->post('testMail', 'Debug::testMailConfig');

// Test controller
$routes->get('test', 'TestController::index');