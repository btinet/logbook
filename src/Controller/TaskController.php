<?php

namespace App\Controller;

use App\AbstractController;
use App\Entity\Task;
use ReflectionException;

class TaskController extends AbstractController
{
    /**
     * @throws ReflectionException
     */
    public function index(): string
    {
        $this->denyUnlessGranted('ROLE_USER');

        $tasks = $this->getRepository(Task::class)->findBy([
            'user_id' => $this->session->get('user'),
        ],[
            'dueDate' => 'ASC'
        ]);

        return $this->render('task/index.html.twig',[
            'flash' => $this->getFlash(),
            'tasks' => $tasks
        ]);
    }
}