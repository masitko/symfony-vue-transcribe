<?php

namespace App\Tests\Integration\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;


class AuthControllerTest extends ApiTestCase
{
    public function testUnauthorisedAPIAccess(): void
    {
        $response = static::createClient()->request('GET', '/api/users');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $this->assertJsonContains(['message' => 'JWT Token not found']);
    }

    public function testUserRegistration(): void
    {
        $response = static::createClient()->request('POST', '/auth/register', ['json' => [
            'name' => 'New User',
            'email' => 'new-user@test.com',
            'password' => 'new-password'
        ]]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $data = $response->toArray();
        $this->assertArrayHasKey('id', $data);
    }

    public function testLoginProcess(): void
    {
        $response = static::createClient()->request('POST', '/auth/login_check', ['json' => [
            'email' => 'test_user@test.com',
            'password' => 'test_password'
        ]]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $data = $response->toArray();
        // make sure the response contains the JWT token and injected user data
        $this->assertArrayHasKey('token', $data);
        $this->assertArrayHasKey('user', $data);
        $this->assertArrayHasKey('id', $data['user']);
    }  


}
