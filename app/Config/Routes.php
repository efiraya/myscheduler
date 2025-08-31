<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

$routes->get('/', 'AuthController::login');

$routes->get('/auth/register', 'AuthController::register');
$routes->post('/auth/registerSubmit', 'AuthController::registerSubmit');
$routes->get('/auth/login', 'AuthController::login');
$routes->post('/auth/loginSubmit', 'AuthController::loginSubmit');
$routes->post('/auth/logout', 'AuthController::logout');
$routes->get('/auth/logout', 'AuthController::logout');
$routes->get('/auth/forgotPassword', 'AuthController::forgotPassword');
$routes->post('/auth/resetPasswordSubmit', 'AuthController::resetPasswordSubmit');
$routes->get('/auth/resetPasswordForm/(:segment)', 'AuthController::resetPasswordForm/$1');
$routes->post('/auth/updatePassword', 'AuthController::updatePassword');

$routes->get('/dashboard', 'DashboardController::dashboard', ['filter' => 'auth']);
$routes->get('/help', 'DashboardController::help', ['filter' => 'auth']);

$routes->get('/user/list/(:num)', 'UserController::index/$1', ['filter' => 'auth']);
$routes->get('/user/list', 'UserController::index', ['filter' => 'auth']);
$routes->get('/user/create', 'UserController::create', ['filter' => 'auth']);
$routes->post('/user/submit', 'UserController::store', ['filter' => 'auth']);
$routes->get('/user/edit/(:num)', 'UserController::edit/$1', ['filter' => 'auth']);
$routes->post('/user/update/(:num)', 'UserController::update/$1', ['filter' => 'auth']);
$routes->get('/user/delete/(:num)', 'UserController::delete/$1', ['filter' => 'auth']);

$routes->get('/activity/input', 'ActivityController::activityInput', ['filter' => 'auth']);
$routes->post('/activity/submit', 'ActivityController::activitySubmit', ['filter' => 'auth']);
$routes->get('/activity/list/(:num)', 'ActivityController::activityList/$1', ['filter' => 'auth']);
$routes->get('/activity/list', 'ActivityController::activityList', ['filter' => 'auth']);
$routes->get('/calculation/result', 'ActivityController::calculationResult', ['filter' => 'auth']);
$routes->get('/activity/edit/(:num)', 'ActivityController::activityEdit/$1', ['filter' => 'auth']);
$routes->post('/activity/update/(:num)', 'ActivityController::activityUpdate/$1', ['filter' => 'auth']);
$routes->get('/activity/delete/(:num)', 'ActivityController::activityDelete/$1', ['filter' => 'auth']);
$routes->get('/activity/status/(:num)', 'ActivityController::activityUpdateStatus/$1');
$routes->get('/activity/schedule', 'ActivityController::scheduleActivity', ['filter' => 'auth']);
$routes->get('/activity/history', 'ActivityController::activityHistory', ['filter' => 'auth']);

$routes->post('logout', 'AuthController::logout', ['as' => 'logout']);
$routes->get('send-email', 'DashboardController::sendEmailReminder');
