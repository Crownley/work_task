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

class ApiTest extends TestCase
{
    
    public function testMainRoute()
    {
        $client = new Client();
        $response = $client->request('GET', 'localhost:8000/adverts');

        $this->assertSame($response->getStatusCode(), 200);
    }
    public function testOneAdvert()
    {
        $client = new Client();
        $response = $client->request('GET', 'localhost:8000/177');

        $this->assertSame($response->getStatusCode(), 200);
    }


    public function testLogin()
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


    public function testDeleteAdvert()
    {   
        $client = new Client();
        try {
            $client->request('DELETE', 'localhost:8000/adverts/177');
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
