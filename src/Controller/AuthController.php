<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class AuthController extends AbstractController
{
    #[Route('/auth/register', name: 'app_user_registration')]
    public function register(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
      $user = new User();
      $user->setName($request->getPayload()->get('name'));
      $user->setEmail($request->getPayload()->get('email'));
      $user->setPassword($request->getPayload()->get('password'));
      
      $userRepository->registerUser($user, $userPasswordHasher);
      
      return $this->json([
        'id' => $user->getId(),
        'name' => $user->getName(),
        'email' => $user->getEmail(),
        'roles' => $user->getRoles()
      ], Response::HTTP_CREATED);
    }
}
