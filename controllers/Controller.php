<?php

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller
 */
class Controller
{
    // Entity manager of Doctrine ORM
    private static $entity_manager;

    /**
     * Initialization of controller.
     * @param EntityManager $entity_manager Entity manager
     * @return void
     */
    public static function init(EntityManager $entity_manager): void
    {
        self::$entity_manager = $entity_manager;
    }

    public static function list(Request $request, Response $response, $args): Response
    {
        try {
            $query_params = $request->getQueryParams();
            $repository = self::$entity_manager->getRepository(Phone::class);

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
    }

    public static function show(Request $request, Response $response, $args): Response
    {
        try {
            $phone = self::$entity_manager->getRepository(Phone::class)->find($args['id']);

            if (empty($phone)) {
                throw new \Exception('Phone entity is not found', 404);
            }

            $content = $phone->toArray();
        } catch(\Exception $e) {
            $content = ['code' => $e->getCode(), 'message' => $e->getMessage()];
        }

        $response->getBody()->write(json_encode($content));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function create(Request $request, Response $response): Response
    {
        try {
            $request_content = $request->getBody()->getContents();

            $phone = new Phone();
            $phone->fromArray(json_decode($request_content, true));

            self::$entity_manager->persist($phone);
            self::$entity_manager->flush();

            $content = ['code' => 200, 'message' => 'Phone (' . $phone->getId() . ') is created successfully.'];
        } catch(\Exception $e) {
            $content = ['code' => $e->getCode(), 'message' => $e->getMessage()];
        }

        $response->getBody()->write(json_encode($content));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function update(Request $request, Response $response, $args): Response
    {
        try {
            $phone = self::$entity_manager->getRepository(Phone::class)->find($args['id']);

            if (empty($phone)) {
                throw new \Exception('Phone entity is not found', 404);
            }

            $request_content = $request->getBody()->getContents();

            $phone->fromArray(json_decode($request_content, true));

            self::$entity_manager->persist($phone);
            self::$entity_manager->flush();

            $content = ['code' => 200, 'message' => 'Phone (' . $phone->getId() . ') is updated successfully.'];
        } catch(\Exception $e) {
            $content = ['code' => $e->getCode(), 'message' => $e->getMessage()];
        }

        $response->getBody()->write(json_encode($content));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function delete(Request $request, Response $response, $args): Response
    {
        try {
            $phone = self::$entity_manager->getRepository(Phone::class)->find($args['id']);

            if (empty($phone)) {
                throw new \Exception('Phone entity is not found', 404);
            }

            self::$entity_manager->remove($phone);
            self::$entity_manager->flush();

            $content = ['code' => 200, 'message' => 'Phone (' . $args['id'] . ') is removed successfully.'];
        } catch(\Exception $e) {
            $content = ['code' => $e->getCode(), 'message' => $e->getMessage()];
        }

        $response->getBody()->write(json_encode($content));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
