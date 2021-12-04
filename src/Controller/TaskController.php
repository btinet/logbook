<?php

namespace App\Controller;

use App\AbstractController;
use App\Entity\Tag;
use App\Entity\Task;
use DateTime;
use Exception;
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
        $doneFilterValue = 'all';
        if($this->request->isPostRequest())
        {
            $dueDateFilterValue = $this->request->post('due_date_filter');
            $tagFilterValue = $this->request->post('tag_filter');
            $doneFilterValue = $this->request->post('done_filter');
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

        $doneValues = [
            'unerledigt' => 0,
            'erledigt' => 1,
        ];

        $tags = $this->getRepository(Tag::class)->findBy([
            'user' => $this->session->get('user'),
        ]);

        return $this->render('task/index.html.twig',[
            'flash' => $this->getFlash(),
            'tasks' => $tasks,
            'tags' => $tags,
            'done_values' => $doneValues,
            'due_dates' => $dueDates,
            'due_date_filter' => $dueDateFilterValue,
            'tag_filter' => $tagFilterValue,
            'done_filter' => $doneFilterValue,
            'title' => 'TMA - Aufgabenübersicht'
        ]);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function new(): string
    {
        $this->denyUnlessGranted('ROLE_USER');

        $tags = $this->getRepository(Tag::class)->findBy([
            'user' => $this->session->get('user'),
        ]);

        if($this->request->isPostRequest() and $this->request->isFormSubmitted())
        {
            $dueDate = new DateTime($this->request->post('due_date'));
            $task = new Task();
            $task->setDescription($this->request->post('description'));
            $task->setUser_id($this->session->get('user'));
            $task->setTag_id($this->request->post('tag_id'));
            $task->setDueDate($dueDate);
            $task->setNotice_user($this->request->post('notice_user'));
            $em = $this->getEntityManager();
            $em->persist($task);
            $this->setFlash(203);
            $this->redirect(302,'task');
        }

        return $this->render('task/new.html.twig',[
            'flash' => $this->getFlash(),
            'title' => 'TMA - Neue Task erstellen',
            'tags' => $tags,
        ]);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function update($id)
    {
        $this->denyUnlessGranted('ROLE_USER');

        if($id)
        {

            $task = $this->getRepository(Task::class)->findOneBy([
                'id' => $id,
                'user_id' => $this->session->get('user'),
            ],[
                'done' => true
            ]);

            if ($task)
            {

                $tags = $this->getRepository(Tag::class)->findBy([
                    'user' => $this->session->get('user'),
                ]);

                if ($this->request->isPostRequest() and $this->request->isFormSubmitted())
                {

                    $task = new Task();
                    $task->setDescription($this->request->post('description'));
                    $task->setTag_id($this->request->post('tag_id'));
                    $dueDate = new DateTime($this->request->post('due_date'));
                    $task->setDueDate($dueDate);
                    $task->setNotice_user($this->request->post('notice_user'));
                    $em = $this->getEntityManager();
                    $em->persist($task,$id);
                    $this->setFlash(203);
                    $this->redirect(302, 'task');
                }

                return $this->render('task/update.html.twig',[
                    'flash' => $this->getFlash(),
                    'title' => 'TMA - Neue Task erstellen',
                    'tags' => $tags,
                    'task' => $task
                ]);

            } else {
                $this->setFlash(412, 'danger');
                $this->redirect(302, 'task');
            }

        }
            $this->setFlash(402, 'warning');
            $this->redirect(302, 'task');
            return false;
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function done($id)
    {
        $this->denyUnlessGranted('ROLE_USER');

        if($id and $this->request->isPostRequest() and $this->request->isFormSubmitted())
        {

            $task = $this->getRepository(Task::class)->findOneBy([
                'id' => $id,
                'user_id' => $this->session->get('user'),
            ],[
                'done' => true
            ]);

            if ($task) {

                $tags = $this->getRepository(Tag::class)->findBy([
                    'user' => $this->session->get('user'),
                ]);

                $task = new Task();
                $task->setDone(true);
                $em = $this->getEntityManager();
                $em->persist($task, $id);
                $this->setFlash(204);
            } else {
                $this->setFlash(412, 'warning');
            }
            $this->redirect(302, 'task');
        }
        $this->setFlash(402, 'warning');
        $this->redirect(302, 'task');
    }

    /**
     * @throws ReflectionException
     */
    public function delete(): void
    {
        $this->denyUnlessGranted('ROLE_USER');
        if($this->request->isPostRequest() and $this->request->isFormSubmitted())
        {
            $tags = $_POST['tasks'];
            $taskRepository = $this->getRepository(Task::class);

            foreach ($tags as $key => $value)
            {
                $task = $taskRepository->findOneBy(['id' => $value,'user_id' => $this->session->get('user')]);
                if($task)
                {
                    $task = new Task();
                    $em = $this->getEntityManager()->remove($task,$value);
                    $this->setFlash(200);

                } else {
                    $this->setFlash(412,'danger');
                }
            }
            $this->redirect(302,'task');
        }
        $this->setFlash(401,'danger');
        $this->redirect(302,'task');
    }
}