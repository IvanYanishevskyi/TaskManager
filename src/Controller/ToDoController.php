<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;

class ToDoController extends AbstractController
{
    // Retrieves the user id from the session.
    private function getSessionUserId(Request $request): ?int
    {
        return $request->getSession()->get('user_id');
    }

    // Finds a task by its id.
    private function getTask(int $taskId, TaskRepository $taskRepository): ?object
    {
        return $taskRepository->find($taskId);
    }

    // Adds a flash message to the session.
    private function createFlashMessage(string $type, string $message): void
    {
        $this->addFlash($type, $message);
    }

    // Handles task creation.
    #[Route('/add_task', name: 'create_task')]
    public function addTask(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userId = $this->getSessionUserId($request);

        // Make a new Task.
        $task = new Task();
        $task->setName($request->request->get('taskName'));
        $task->setUsersId($userId);
        $task->setDescription($request->request->get('taskDescription'));
        $task->setDate($request->request->get('taskDate'));
        $task->setCreateAt(new \DateTimeImmutable());

        // Save the task to the database.
        $entityManager->persist($task);
        $entityManager->flush();

        // Provide feedback to the user.
        $this->createFlashMessage('success', 'Task created successfully!');
        return $this->redirectToRoute('todo-page');
    }

    // Displays tasks for today.
    #[Route('/today', name: 'today_tasks')]
    public function getTodayTasks(TaskRepository $taskRepository, Request $request): Response
    {
        $userId = $this->getSessionUserId($request);
        $today = (new \DateTime())->format('Y-m-d');

        // Retrieve tasks for today.
        $tasks = $taskRepository->findByDate($userId, $today);
        return $this->render('todo/today_tasks.html.twig', [
            'tasks' => $tasks,
        ]);
    }
    // Retrieve tasks for other days.
    #[Route('/not_today', name: 'not_today_tasks')]
    public function getNotTodayTasks(TaskRepository $taskRepository, Request $request): Response
    {
    $userId = $this->getSessionUserId($request);
    $today = (new \DateTime())->format('Y-m-d');

        $notTodayTasks = $taskRepository->findByNotDate($userId, $today);

    return $this->render('todo/not_today_tasks.html.twig', [
        'tasks' => $notTodayTasks,
    ]);
}


    // Deletes a task by its id.
    #[Route('/task/delete', name: 'delete_task')]
    public function deleteTask(Request $request, TaskRepository $taskRepository, EntityManagerInterface $entityManager): Response
    {
        $taskId = $request->request->get('id');
        $pageToRedirect  = $request->request->get('page');

        $task = $this->getTask($taskId, $taskRepository);
        // If task is found deletes it.
        if ($task) {
            $entityManager->remove($task);
            $entityManager->flush();
            $this->createFlashMessage('success', 'Task deleted successfully.');
        } else {
            $this->createFlashMessage('error', 'Task not found.');
        }

        return $this->redirectToRoute($pageToRedirect );
    }

    // Shows the form to edit a task.
    #[Route('/task/edit/{id}', name: 'edit_task')]
    public function editTask(int $id, TaskRepository $taskRepository, Request $request): Response
    {
        $task = $this->getTask($id, $taskRepository);
        $referer = $request->headers->get('referer');
        // If task is not found throws an error.
        if (!$task) {
            $this->createFlashMessage('error', 'Task not found.');
            return $this->redirectToRoute('todo-page');
        }

        return $this->render('todo/edit_task.html.twig', [
            'task' => $task,
            'referer' => $referer,
        ]);
    }

    // Updates a task.
    #[Route('/task/update', name: 'update_task')]
    public function updateTask(Request $request, TaskRepository $taskRepository, EntityManagerInterface $entityManager): Response
    {
        $taskId = $request->request->get('taskId');
        $referer = $request->request->get('referer');

        $task = $this->getTask($taskId, $taskRepository);
        // If task is found update it.
        if ($task) {
            $task->setName($request->request->get('taskName'));
            $task->setDescription($request->request->get('taskDescription'));
            $task->setDate($request->request->get('taskDate'));

            $entityManager->flush();
            $this->createFlashMessage('success', 'Task updated successfully.');
        } else {
            $this->createFlashMessage('error', 'Task not found.');
        }
        // If refer to page exists, redirect to it.
        if ($referer) {
            return $this->redirect($referer);
        }
        return $this->redirectToRoute('todo-page');    }
}