<?php

namespace App\Controller;

class AppController
{


    public function index($id = false): string
    {
        return 'index Seite';
    }

    public function edit($id = false): string
    {
        return 'Bearbeiten Seite '.$id;
    }

}