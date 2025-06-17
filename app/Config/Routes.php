<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->post('calendly/fetch', 'CalendlyController::fetchAvailability');
$routes->get('/', 'PageController::index');
