<?php

namespace App\Controller;

use App\AbstractController;
use App\Entity\User;

class AppController extends AbstractController
{

    public function index(): string
    {
        $userRepository = $this->getRepository(User::class);
        $userAll = $userRepository->findAll();

        return $this->render('app/index.html.twig',[
            'user_all' => $userAll
        ]);
    }

    /**
     * @param int|null $id
     * @return string
     */
    public function edit(int $id = null): string
    {
        $this->session->set('username','Ben');

        return $this->render('app/index.html.twig',[
            'id' => $id
        ]);
    }

}
