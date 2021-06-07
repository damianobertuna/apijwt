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

    /**
     * to pass this test is mandatory to insert on the header a valid API key
     */
    public function test_change_new_password_not_empty(): void
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
    
        try {      
            $res = $client->request('POST', 'localhost/apijwt/index.php', [
                'headers'   => [
                    'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MjMwODg0NDYsImlzcyI6ImxvY2FsaG9zdCIsImV4cCI6MTYyMzA5MjA0Nn0.e1GOxRQqtgnGRMUbtSO-pY4W4GjqBSx1GjtOz_D4Ngk'
                ],
                'json'      => $loginJson,                
            ]);
        } catch (ServerException $e) {
            $response = $e->getResponse();
            $responseBodyAsString   = $response->getBody()->getContents();
            $responseBodyAsObj      = json_decode($responseBodyAsString);
            $this->assertEquals(500, $responseBodyAsObj->response->status);
            $this->assertEquals("API param new password is required", $responseBodyAsObj->response->message);
        }
    }

    public function test_only_post_request_is_valid(): void 
    {        
        $client = new GuzzleHttp\Client();
    
        try {      
            $res = $client->request('GET', 'localhost/apijwt/index.php');
        } catch (Exception $e) {
            $response = $e->getResponse();
            $responseBodyAsString   = $response->getBody()->getContents();
            $responseBodyAsObj      = json_decode($responseBodyAsString);
            $this->assertEquals(400, $responseBodyAsObj->response->status);
            $this->assertEquals("Request Method is not valid", $responseBodyAsObj->response->message);
        }
    }

}