<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../bootstrap.php';

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$error_middleware = $app->addErrorMiddleware(true, true, true);
$error_middleware->setDefaultErrorHandler(function($request, $exception) {
    $content = ['code' => $exception->getCode(), 'message' => $exception->getMessage()];

    return new \GuzzleHttp\Psr7\Response(500, ['Content-Type' => 'application/json'], json_encode($content));
});

\Controller::init($entity_manager);

$app->get('/phone[/{offset:\d+}/{amount:\d+}]', Controller::class . '::list');
$app->get('/phone/{id:\d+}', Controller::class . '::show');
$app->post('/phone', Controller::class . '::create');
$app->put('/phone/{id:\d+}', Controller::class . '::update');
$app->delete('/phone/{id:\d+}', Controller::class . '::delete');

$app->run();