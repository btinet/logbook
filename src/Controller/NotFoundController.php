<?php

namespace App\Controller;

use App\AbstractController;

class NotFoundController extends AbstractController
{
    public function index($path): string
    {
        return $this->render('messages/not_found.html.twig',[
            'path' => $path,
            'host' => host,
            'title' => 'TMA - Seite nicht gefunden!'
        ]);
    }
}