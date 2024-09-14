<?php
namespace App\Controller;

use App\Entity\Users; // Добавьте этот use-стейтмент
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class RegisterController extends AbstractController
{
    #[Route('/register-success', name: 'store')]
    public function register_process(Request $request, EntityManagerInterface $entityManager): Response
    {
        $username = $request->request->get('name');
        $email = $request->request->get('floatingInput');
        $password = $request->request->get('floatingPassword');
        $passwordConf = $request->request->get('floatingPasswordConf');
        $existingUser = $entityManager->getRepository(Users::class)->findOneBy([
            'email' => $email
        ]);
        if ($existingUser){
            $this->addFlash('erorr', 'User with this email is already registered');
        }
        if ($password !== $passwordConf) {
            $this->addFlash('error', 'Passwords are not equal');
            return $this->render('/login_register/register.html.twig');
        } else {
            $hashedpass = password_hash($password, PASSWORD_DEFAULT);
            $user = new Users();
            $user->setName($username);
            $user->setEmail($email);
            $user->setPassword($hashedpass);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Registration success');

            $session = $request->getSession();
            $session->set('username', $username);
            $session->set('user_id', $user->getId());

            return $this->redirectToRoute('home');
        }
    }
}
