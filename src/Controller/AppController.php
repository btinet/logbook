<?php

namespace App\Controller;

use App\AbstractController;

class AppController extends AbstractController
{

    /**
     * @return string
     */
    public function index(): string
    {
        return $this->render('app/index.html.twig');
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
