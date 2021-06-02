<?php 
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ApiTest extends TestCase
{
    public function test_username_is_requred(): void
    {
        $loginJson = array(
            "name"  => "Login",
            "param" => array(
                "username"     => "",
                "password"  => "12345"
            )
        );

        $api = new Api();
        $api->request = json_encode($loginJson);
        $response = $api->processApi();
        $response = json_decode($response);
        $this->assertEquals(400, $response->error->status);
    }

}