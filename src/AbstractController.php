<?php

namespace App;



use App\Extension\Twig\TwigExtension;
use Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class AbstractController
{

    /**
     * @var Environment
     */
    protected Environment $view;

    function __construct(){

        $debug = true;

        $loader = new FilesystemLoader(project_root.'/templates');
        $this->view = new Environment($loader, [
            'cache' => project_root.'/var/cache',
            'debug' => $debug
        ]);

        if ($debug){
            $this->view->addExtension(new \Twig\Extension\DebugExtension());
        }
        $this->view->addExtension(new TwigExtension());
    }

    /**
     * @param $template
     * @param array $options
     */
    public function render($template, Array $options = [] ){

        try {
            return $this->view->render($template, $options);
        } catch (Exception $e) {
            return 'Template nicht gefunden, oder so.';
        }
    }
}