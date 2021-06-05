<?php 
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ApiTest extends TestCase
{
    public function test_username_is_requred(): void
    {
        $loginJson = array(
            "name"  => "actionLogin",
            "param" => array(
                "username"     => "",
                "password"  => "12345"
            )
        );

        $responseObj = new Response;

        $api = new Api($responseObj);
        $api->request = json_encode($loginJson);
        $api->processApi();
        //$response = json_decode($response);
        $this->assertEquals(400, $responseObj->getStatus());
    }

}