<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ApiTest extends TestCase
{


    // Checking GET method on adverts
    public function testMainRoute()
    {
        $client = new Client();
        $response = $client->request('GET', 'localhost:8000/adverts');

        $this->assertSame($response->getStatusCode(), 200);
    }

    // Checking GET method on single Advert
    public function testOneAdvert()
    {
        $client = new Client();
        $response = $client->request('GET', 'localhost:8000/adverts/177');

        $this->assertSame($response->getStatusCode(), 200);
    }

    // Testing Login for user
    public function testLoginUser()
    {
        $client = new Client();

        $response = $client->request('POST', 'localhost:8000/login', [
            'form_params' => [
                'email' => 'test@test.com',
                'password' => '123456'
            ]
        ]);
        $this->assertSame($response->getStatusCode(), 200);
    }

    // Testing Register for user
    public function testRegister()
    {
        $client = new Client();
        $request = Request::createFromGlobals();
        var_dump($request->cookies->get('PHPSESSID'));
        $response = $client->request('POST', 'localhost:8000/register', [
            'form_params' => [
                'email' => 'teqs4t@t1es2t3.com',
                'password' => '123456',
                'password_confirmation' => '123456'
            ]
        ]);
        $this->assertSame($response->getStatusCode(), 200);
    }


    // Testing DELETE and PUT methods
    public function testDeleteAndPutAdvert()
    {   
        $methods = ['DELETE', 'PUT'];
        foreach ($methods as $method) {
            $client = new Client();
            try {
                $client->request($method, 'localhost:8000/adverts/177');
            } catch (ClientException $e) {
                $response = Psr7\str($e->getResponse());
            }
            if( strpos($response, 'Access Denied') !== false) {
                $value = true;
            } else {
                $value = false;
            }
            $this->assertTrue($value);
        }
    }

}
