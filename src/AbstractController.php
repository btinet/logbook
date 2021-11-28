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
    public Session $session;

    function __construct(){

        $debug = ($_ENV['APP_ENV'] === 'development') ? true : false;

        $this->session = new Session();
        $this->session->init();

        $loader = new FilesystemLoader(project_root.'/templates');
        $this->view = new Environment($loader, [
            'cache' => project_root.'/var/cache',
            'debug' => $debug
        ]);

        if ($debug){
            $this->view->addExtension(new \Twig\Extension\DebugExtension());
        }
        $this->view->addExtension(new TwigExtension());
        $this->view->addGlobal('session',$this->session);
    }

    /**
     * @param $template
     * @param array $options
     * @return string
     */
    public function render($template, Array $options = [] ): string
    {
        try {
            return $this->view->render($template, $options);
        } catch (Exception $e) {
            return 'Template nicht gefunden, oder so.';
        }
    }
}
