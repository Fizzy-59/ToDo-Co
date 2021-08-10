<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/tasks', name: "task_list")]
    public function getTasks(): Response
    {
        return $this->render('task/list.html.twig',
            ['tasks' => $this->getDoctrine()->getRepository(Task::class)->findAll()]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    #[Route('/tasks/create', name: "task_create")]
    public function createTask(Request $request): RedirectResponse|Response
    {
        $user = $this->getUser();
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $task->setUser($user);

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Task $task
     * @param Request $request
     * @return RedirectResponse|Response
     */
    #[Route('/tasks/{id}/edit', name: "task_edit")]
    public function editTask(Task $task, Request $request): RedirectResponse|Response
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @param Task $task
     * @return RedirectResponse
     */
    #[Route('/tasks/{id}/toggle', name: "task_toggle")]
    public function toggleTask(Task $task): RedirectResponse
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * @param Task $task
     * @return RedirectResponse
     */
    #[Route('/tasks/{id}/delete', name: "task_delete")]
    public function deleteTask(Task $task): RedirectResponse
    {
        $currentUser = $this->getUser();
        $taskUser = $task->getUser();
        $role = $currentUser->getRoles();

        if($taskUser === null && $role[0] !== "ROLE_ADMIN") {
            $this->addFlash('error', 'Une tâche anonyme ne peut-être supprimé que par un administrateur.');
            return $this->redirectToRoute('task_list');
        }

        if($currentUser !== $taskUser && $taskUser !== null) {
            $this->addFlash('error', 'La tâche ne peut-être supprimé que par son propriétaire.');
            return $this->redirectToRoute('task_list');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
