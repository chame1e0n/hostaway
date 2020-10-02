<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../bootstrap.php';

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

\Controller::init($entity_manager);

$app->get('/phone[/{offset:\d+}/{amount:\d+}]', Controller::class . '::list');
$app->get('/phone/{id:\d+}', Controller::class . '::show');
$app->post('/phone', Controller::class . '::create');
$app->put('/phone/{id:\d+}', Controller::class . '::update');
$app->delete('/phone/{id:\d+}', Controller::class . '::delete');

$app->run();