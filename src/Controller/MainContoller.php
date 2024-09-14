<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainContoller extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {

        return $this->render('/homepage/homepage.html.twig');
    }

    #[Route('/login', name: 'login-page')]
    public function login_header(): Response
    {
        return $this->render('/login_register/login.html.twig');
    }

    #[Route('/register', name: 'registration-page')]
    public function register_header(): Response
    {
        return $this->render('/login_register/register.html.twig');
    }

    #[Route('/logout', name: 'logout-process')]
    public function logout_header(SessionInterface $session): Response
    {
        $session->invalidate();
        return $this->redirectToRoute('home');
    }
    #[Route('/pricing',name:'pricing-page')]
    public function pricing_header():Response
    {
        return $this->render('/homepage/pricing.html.twig');
    }
    #[Route('/todo',name:'todo-page')]
    public function todo_app(TaskRepository $taskRepository,Request $request):Response{
        $session = $request->getSession();
        if ($session->has('user_id')){
            $id = $session->get('user_id');
            $tasks = $taskRepository->findByUserId($id);
            return $this->render('/todo/todo.html.twig',[
                'tasks' => $tasks,
            ]);
        }
        else{
            $this->addFlash('error','You need to register first.');
            return $this->redirectToRoute('registration-page');
        }
    }
}
