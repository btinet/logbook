<?php

namespace App\Controller;

use App\AbstractController;
use ReflectionException;

class AppController extends AbstractController
{

    /**
     * @throws ReflectionException
     */
    public function index(): void
    {
        $this->denyUnlessGranted('ROLE_USER');
        $this->redirect(302,'task');
    }


    public function imprint(): string
    {
        return $this->render('app/impressum.html.twig',[
            'flash' => $this->getFlash(),
            'title' => 'TMA - Impressum'
        ]);
    }

    public function tos(): string
    {
        return $this->render('app/terms_of_use.html.twig',[
            'flash' => $this->getFlash(),
            'title' => 'TMA - Nutzungsbedingungen'
        ]);
    }

}
