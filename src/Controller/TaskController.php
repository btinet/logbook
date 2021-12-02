<?php

namespace App\Controller;

use App\AbstractController;
use App\Entity\Tag;
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

        $dueDateFilterValue = 'all';
        $tagFilterValue = 'all';
        if($this->request->isPostRequest())
        {
            $dueDateFilterValue = $this->request->post('due_date_filter');
            $tagFilterValue = $this->request->post('tag_filter');
        }

        $tasks = $this->getRepository(Task::class)->findByAndJoinTags([
            'user_id' => $this->session->get('user'),
        ],[
            'dueDate' => 'ASC'
        ]);

        $dueDates = [
            'vorgestern' => -2,
            'gestern' => -1,
            'heute' => 0,
            'morgen' => 1,
            'übermorgen' => 2
        ];

        $tags = $this->getRepository(Tag::class)->findBy([
            'user' => $this->session->get('user'),
        ]);


        return $this->render('task/index.html.twig',[
            'flash' => $this->getFlash(),
            'tasks' => $tasks,
            'tags' => $tags,
            'due_dates' => $dueDates,
            'due_date_filter' => $dueDateFilterValue,
            'tag_filter' => $tagFilterValue,
            'title' => 'TMA - Aufgabenübersicht'
        ]);
    }
}