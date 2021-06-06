<?php 
declare(strict_types=1);

require_once('src/autoload.php');

use GuzzleHttp\Exception\ServerException;
use PHPUnit\Framework\TestCase;

final class ApiTest extends TestCase
{
    public function test_username_is_requred(): void
    {        
        $loginJson = array(
            "name"  => "actionLogin",
            "param" => array(
                "username"     => "",
                "password"  => "newpassword"
            )
        );

        $client = new GuzzleHttp\Client();
        try {      
            $client->request('POST', 'localhost/apijwt/index.php', [
                'json' => $loginJson
            ]);
        } catch (ServerException $e) {
            $response = $e->getResponse();
            $responseBodyAsString   = $response->getBody()->getContents();
            $responseBodyAsObj      = json_decode($responseBodyAsString);            
            $this->assertEquals(500, $responseBodyAsObj->response->status);
            $this->assertEquals("Invalid user or password", $responseBodyAsObj->response->message);
        }
    }

    public function test_password_is_requred(): void
    {        
        $loginJson = array(
            "name"  => "actionLogin",
            "param" => array(
                "username"     => "test@email.com",
                "password"  => ""
            )
        );

        $client = new GuzzleHttp\Client();
        try {      
            $client->request('POST', 'localhost/apijwt/index.php', [
                'json' => $loginJson
            ]);
        } catch (ServerException $e) {
            $response = $e->getResponse();
            $responseBodyAsString   = $response->getBody()->getContents();
            $responseBodyAsObj      = json_decode($responseBodyAsString);            
            $this->assertEquals(500, $responseBodyAsObj->response->status);
            $this->assertEquals("Invalid user or password", $responseBodyAsObj->response->message);
        }
    }

    public function test_get_resource_only_logged_in_user(): void
    {   
        $loginJson = array(
            "name"  => "actionGetResource",
            "param" => array(
                "id"     => "123",                
            )
        );

        $client = new GuzzleHttp\Client();
        try {      
            $client->request('POST', 'localhost/apijwt/index.php', [
                'json' => $loginJson
            ]);
        } catch (ServerException $e) {
            $response = $e->getResponse();
            $responseBodyAsString   = $response->getBody()->getContents();
            $responseBodyAsObj      = json_decode($responseBodyAsString);
            //var_dump($responseBodyAsObj);
            $this->assertEquals(500, $responseBodyAsObj->response->status);
            $this->assertEquals("Access Token Not found", $responseBodyAsObj->response->message);
        }
    }

    public function test_change_password_new_not_empty(): void
    {   
        $loginJson = array(
            "name"  => "actionChangePassword",
            "param" => array(
                "username"        => "test@email.com",          
                "oldPassword"     => "mypassword",
                "newPassword"     => "",
            )
        );

        $client = new GuzzleHttp\Client();
        $jar = \GuzzleHttp\Cookie\CookieJar::fromArray(
            [
                'JwtRefreshToken' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MjI5NjAyMzAsImlzcyI6ImxvY2FsaG9zdCIsImV4cCI6MTYyMzU2NTAzMH0.wTbwVq8dg6718-E4f9FFv5SS-xwq6YfhSXf7rWtazUw',
            ],
            'localhost'
        );
        try {      
            $res = $client->request('POST', 'localhost/apijwt/index.php', [
                'json' => $loginJson,
                'cookies' => $jar
            ]);
        } catch (ServerException $e) {
            $response = $e->getResponse();
            $responseBodyAsString   = $response->getBody()->getContents();
            $responseBodyAsObj      = json_decode($responseBodyAsString);
            var_dump($responseBodyAsObj);
            $this->assertEquals(500, $responseBodyAsObj->response->status);
            $this->assertEquals("Access Token Not found", $responseBodyAsObj->response->message);
        }
    }

}