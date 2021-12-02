<?php

namespace App\Controller;

use App\AbstractController;

class AppController extends AbstractController
{

    /**
     * @throws \ReflectionException
     */
    public function index(): void
    {
        $this->denyUnlessGranted('ROLE_USER');
        $this->redirect(302,'task');
    }

}
