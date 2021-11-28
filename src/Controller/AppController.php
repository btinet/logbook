<?php

namespace App\Controller;

use App\AbstractController;

class AppController extends AbstractController
{


    /**
     * @param false $id
     * @return string
     */
    public function index(bool $id = false): string
    {
        return $this->render('base.html.twig');
    }

    public function edit($id = false): string
    {
        return $this->render('base.html.twig',[
            'id' => $id
        ]);
    }

}