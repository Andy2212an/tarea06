<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


// Rutas para reportes
$routes->get('reporte', 'ReporteController::index');
$routes->post('reporte/datos', 'ReporteController::obtenerDatos');
$routes->post('reporte/generar', 'ReporteController::generarPDF');


// Dashboard Publishers
$routes->get('/dashboard/informePublishers', 'DashboardContrroller::getInformePublishers');

// API
$routes->get('public/api/getDataPublishersCache', 'DashboardContrroller::getDataPublishersCache');

$routes->get('reporte/pesos_publishers', 'ReporteController::pesos_publishers');
$routes->get('reporte/pesos_datos', 'ReporteController::pesos_datos');
