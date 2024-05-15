<?php

namespace App\Tests\App\EventListener;

use PHPUnit\Framework\TestCase;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\EventListener\LoginSuccessListener;
use Symfony\Component\EventDispatcher\EventDispatcher;

class LoginSuccessListenerTest extends TestCase
{
  private User $testUser;
  private Response $testResponse;

  public function setUp(): void
  {
    $this->testUser = new User();
    $this->testUser
      ->setId(1)
      ->setName('Test User')
      ->setEmail('test@test.com')
      ->setPassword('test-password');
      $this->testResponse = new Response();
  }

  public function testUserInfoExistsInPayload(): void
  {
    $event = new AuthenticationSuccessEvent(
      ['data' => ['token' => 'test-token']],
      $this->testUser,
      $this->testResponse
    );

    $listener = new LoginSuccessListener();

    $dispatcher = new EventDispatcher();
    $dispatcher->addListener(
      'lexik_jwt_authentication.on_authentication_success',
      [$listener, 'onLoginSuccess']
    );
    $dispatcher->dispatch($event, 'lexik_jwt_authentication.on_authentication_success');
    $result = $event->getData();

    $this->assertArrayHasKey('token', $result['data']);
    $this->assertArrayHasKey('user', $result);
    $this->assertArrayHasKey('id', $result['user']);
    $this->assertArrayNotHasKey('password', $result['user']);
  }
}
