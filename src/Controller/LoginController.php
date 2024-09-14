<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Users;
use Symfony\Component\HttpFoundation\Request;


class LoginController extends AbstractController
{
    #[Route('/login-process', name: 'login-process')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $email = $request->request->get('floatingInput');
        $password = $request->request->get('floatingPassword');
        $user = $entityManager->getRepository(Users::class)->findOneBy([
            'email' => $email
        ]);

        if(!$user){
            $this->addFlash('error', 'Неправильное имя пользователя или пароль');
            return $this->redirectToRoute('login-page');
        }
        $storedPasswordHash = $user->getPassword();
        if (password_verify($password,$storedPasswordHash)){
        $session = $request->getSession();
        $session->set('username', $user->getName());
        $session->set('user_id', $user->getId());
            return $this->redirectToRoute('home');
         }
        else{
            $this->addFlash('error','Неправильное имя пользователя или пароль');
            return $this->redirectToRoute('login-page');
        }
    }
}
