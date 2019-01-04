<?php


namespace App\Tests\Controller;

use App\Controller\UserController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiPersonaControllerTest
 *
 * @package App\Tests\Controller
 * @coversDefaultClass \App\Controller\UserController
 */
class UserControllerTest extends WebTestCase
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
    public function testPostUser201(): array
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

        $user = $datosRecibidos['user'];
        $this->userAssertArrayHasKeys($user);

        return $datos;
    }

    /**
     * @depends testPostUser201
     * @param array $datos
     */
    public function testPostUser400(array $datos)
    {
        self::$client->request(
            Request::METHOD_POST,
            UserController::API_USERS_PATH,
            [], [], [], json_encode($datos)
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_BAD_REQUEST,
            $response->getStatusCode()
        );
        self::assertJson($response->getContent());

        $datosRecibidos = json_decode($response->getContent(), true);
        self::assertArrayHasKey('message', $datosRecibidos);

        $message = $datosRecibidos['message'];
        $this->messageAssertArrayHasKeys($message);
    }

    /**
     *
     */
    public function testPostUser422()
    {
        $username = '';
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
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $response->getStatusCode()
        );
        self::assertJson($response->getContent());

        $datosRecibidos = json_decode($response->getContent(), true);
        self::assertArrayHasKey('message', $datosRecibidos);

        $message = $datosRecibidos['message'];
        $this->messageAssertArrayHasKeys($message);
    }

    /**
     */
    public function testGetUsers200(): string
    {
        self::$client->request(
            Request::METHOD_GET,
            UserController::API_USERS_PATH
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_OK,
            $response->getStatusCode()
        );
        self::assertJson($response->getContent());

        $datosRecibidos = json_decode($response->getContent(), true);
        self::assertArrayHasKey('users', $datosRecibidos);

        $users = $datosRecibidos['users'];
        $user = $users[0];
        $this->userAssertArrayHasKeys($user);

        return $user['id'];
    }

    /**
     * @depends testGetUsers200
     * @param array $datos
     * @return array
     */
    public function testGetUser200(string $id): array
    {
        self::$client->request(
            Request::METHOD_GET,
            UserController::API_USERS_PATH . '/' . $id
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_OK,
            $response->getStatusCode()
        );
        self::assertJson($response->getContent());

        $datosRecibidos = json_decode($response->getContent(), true);
        self::assertArrayHasKey('user', $datosRecibidos);

        $user = $datosRecibidos['user'];
        $this->userAssertArrayHasKeys($user);

        return $user;
    }

    /**
     */
    public function testGetUser404()
    {
        self::$client->request(
            Request::METHOD_GET,
            UserController::API_USERS_PATH . '/' . microtime()
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_NOT_FOUND,
            $response->getStatusCode()
        );
        self::assertJson($response->getContent());

        $datosRecibidos = json_decode($response->getContent(), true);
        self::assertArrayHasKey('message', $datosRecibidos);

        $message = $datosRecibidos['message'];
        $this->messageAssertArrayHasKeys($message);
    }

    /**
     * @depends testGetUser200
     * @param array $datos
     * @return array
     */
    public function testPutUser200(array $datos): array
    {
        $username = 'rme' . microtime();
        $datos['username'] = $username;
        $datos['email'] = $username . '@alumnos.upm.es';
        $datos['password'] = '123456';

        self::$client->request(
            Request::METHOD_PUT,
            UserController::API_USERS_PATH . '/' . $datos['id'],
            [], [], [], json_encode($datos)
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_OK,
            $response->getStatusCode()
        );
        self::assertJson($response->getContent());

        $datosRecibidos = json_decode($response->getContent(), true);
        self::assertArrayHasKey('user', $datosRecibidos);

        $user = $datosRecibidos['user'];
        $this->userAssertArrayHasKeys($user);

        return $user;
    }

    public function testPutUser404()
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
            Request::METHOD_PUT,
            UserController::API_USERS_PATH . '/' . microtime(),
            [], [], [], json_encode($datos)
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_NOT_FOUND,
            $response->getStatusCode()
        );
        self::assertJson($response->getContent());

        $datosRecibidos = json_decode($response->getContent(), true);
        self::assertArrayHasKey('message', $datosRecibidos);

        $message = $datosRecibidos['message'];
        $this->messageAssertArrayHasKeys($message);
    }

    /**
     * @depends testGetUser200
     * @param array $datos
     */
    public function testPutUser422(array $datos)
    {
        $username = 'rme' . microtime();
        $datos['username'] = $username;
        $datos['email'] = '';

        self::$client->request(
            Request::METHOD_PUT,
            UserController::API_USERS_PATH . '/' . $datos['id'],
            [], [], [], json_encode($datos)
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $response->getStatusCode()
        );
        self::assertJson($response->getContent());

        $datosRecibidos = json_decode($response->getContent(), true);
        self::assertArrayHasKey('message', $datosRecibidos);

        $message = $datosRecibidos['message'];
        $this->messageAssertArrayHasKeys($message);
    }

    /**
     * @depends testGetUser200
     * @param array $datos
     * @return string
     */
    public function testDeleteUser204(array $datos): string
    {
        $id = $datos['id'];
        self::$client->request(
            Request::METHOD_DELETE,
            UserController::API_USERS_PATH . '/' . $id
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_NO_CONTENT,
            $response->getStatusCode()
        );

        return $id;
    }

    /**
     * @depends testDeleteUser204
     * @param string $id
     */
    public function testDeleteUser404(string $id)
    {
        self::$client->request(
            Request::METHOD_DELETE,
            UserController::API_USERS_PATH . '/' . $id
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_NOT_FOUND,
            $response->getStatusCode()
        );
    }

    /**
     * @param $user
     */
    private function userAssertArrayHasKeys($user): void
    {
        self::assertArrayHasKey('id', $user);
        self::assertArrayHasKey('username', $user);
        self::assertArrayHasKey('email', $user);
        self::assertArrayHasKey('enabled', $user);
        self::assertArrayHasKey('isAdmin', $user);
    }

    /**
     * @param $message
     */
    private function messageAssertArrayHasKeys($message): void
    {
        self::assertArrayHasKey('message', $message);
        self::assertArrayHasKey('code', $message);
    }
}
