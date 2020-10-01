<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../bootstrap.php';

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

$app->get('/phone[/{offset:\d+}/{amount:\d+}]', function (Request $request, Response $response, $args) use ($entity_manager) {
    try {
        $query_params = $request->getQueryParams();
        $repository = $entity_manager->getRepository(Phone::class);

        $amount = $args['amount'] ?? null;
        $offset = $args['offset'] ?? null;

        $phones = $repository->findBy($query_params, [], $amount, $offset);

        $content = [];
        foreach($phones as /* @var $phone Phone */ $phone) {
            $content[] = $phone->toArray();
        }
    } catch(\Exception $e) {
        $content = ['code' => $e->getCode(), 'message' => $e->getMessage()];
    }

    $response->getBody()->write(json_encode($content));

    return $response->withHeader('Content-Type', 'application/json');
});
$app->get('/phone/{id:\d+}', function (Request $request, Response $response, $args) use ($entity_manager) {
    try {
        $phone = $entity_manager->getRepository(Phone::class)->find($args['id']);

        if (empty($phone)) {
            throw new \Exception('Phone entity is not found', 404);
        }

        $content = $phone->toArray();
    } catch(\Exception $e) {
        $content = ['code' => $e->getCode(), 'message' => $e->getMessage()];
    }

    $response->getBody()->write(json_encode($content));

    return $response->withHeader('Content-Type', 'application/json');
});
$app->post('/phone', function (Request $request, Response $response) use ($entity_manager) {
    try {
        $request_content = $request->getBody()->getContents();

        $phone = new Phone();
        $phone->fromArray(json_decode($request_content, true));

        $entity_manager->persist($phone);
        $entity_manager->flush();

        $content = ['code' => 200, 'message' => 'Phone (' . $phone->getId() . ') is created successfully.'];
    } catch(\Exception $e) {
        $content = ['code' => $e->getCode(), 'message' => $e->getMessage()];
    }

    $response->getBody()->write(json_encode($content));

    return $response->withHeader('Content-Type', 'application/json');
});
$app->put('/phone/{id:\d+}', function (Request $request, Response $response, $args) use ($entity_manager) {
    try {
        $phone = $entity_manager->getRepository(Phone::class)->find($args['id']);

        if (empty($phone)) {
            throw new \Exception('Phone entity is not found', 404);
        }

        $request_content = $request->getBody()->getContents();

        $phone->fromArray(json_decode($request_content, true));

        $entity_manager->persist($phone);
        $entity_manager->flush();

        $content = ['code' => 200, 'message' => 'Phone (' . $phone->getId() . ') is updated successfully.'];
    } catch(\Exception $e) {
        $content = ['code' => $e->getCode(), 'message' => $e->getMessage()];
    }

    $response->getBody()->write(json_encode($content));

    return $response->withHeader('Content-Type', 'application/json');
});
$app->delete('/phone/{id:\d+}', function (Request $request, Response $response, $args) use ($entity_manager) {
    try {
        $phone = $entity_manager->getRepository(Phone::class)->find($args['id']);

        if (empty($phone)) {
            throw new \Exception('Phone entity is not found', 404);
        }

        $entity_manager->remove($phone);
        $entity_manager->flush();

        $content = ['code' => 200, 'message' => 'Phone (' . $args['id'] . ') is removed successfully.'];
    } catch(\Exception $e) {
        $content = ['code' => $e->getCode(), 'message' => $e->getMessage()];
    }

    $response->getBody()->write(json_encode($content));

    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();