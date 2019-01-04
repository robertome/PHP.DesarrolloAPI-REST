<?php


namespace App\Tests\Controller;

use App\Controller\ResultController;
use App\Controller\UserController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiPersonaControllerTest
 *
 * @package App\Tests\Controller
 * @coversDefaultClass \App\Controller\ResultController
 */
class ResultControllerTest extends WebTestCase
{
    /** @var Client $client */
    private static $client;

    public static function setUpBeforeClass()
    {
        self::$client = static::createClient();
    }

    /**
     *
     * @return array
     * @throws \Exception
     */
    public function testPostUser201(): string
    {
        $username = 'rme' . microtime();
        $datos = [
            'username' => $username,
            'email' => $username . '@alumnos.upm.es',
            'password' => '123456',
            'enabled' => 'false',
            'isAdmin' => 'true'
        ];

        self::$client->request(
            Request::METHOD_POST,
            UserController::API_USERS_PATH,
            [], [], [], json_encode($datos)
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_CREATED,
            $response->getStatusCode()
        );
        self::assertJson($response->getContent());

        $datosRecibidos = json_decode($response->getContent(), true);
        self::assertArrayHasKey('user', $datosRecibidos);

        return $datosRecibidos['user']['id'];
    }

    /**
     * @depends testPostUser201
     * @param string $userId
     */
    public function testPostResult201(string $userId)
    {
        $datos = [
            'result' => 7,
            'userId' => $userId
        ];

        self::$client->request(
            Request::METHOD_POST,
            ResultController::API_RESULTS_PATH,
            [], [], [], json_encode($datos)
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_CREATED,
            $response->getStatusCode()
        );
        self::assertJson($response->getContent());

        $datosRecibidos = json_decode($response->getContent(), true);
        self::assertArrayHasKey('result', $datosRecibidos);
    }


}
