<?php

namespace App;


use App\Extension\Twig\TwigExtension;
use App\Service\EntityManagerService;
use Exception;
use ReflectionClass;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class AbstractController
{

    /**
     * @var Environment
     */
    protected Environment $view;
    protected Session $session;
    protected Request $request;

    function __construct(){

        $debug = $_ENV['APP_ENV'] === 'development';

        $this->session = new Session();
        $this->session->init();

        $this->request = new Request($this->session->get('csrf_token'));
        $this->generateToken();

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
        $this->view->addGlobal('csrf_token',$this->session->get('csrf_token'));
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

    public function getEntityManager(): EntityManagerService
    {
        return new EntityManagerService();
    }

    /**
     * @throws \ReflectionException
     */
    public function getRepository($entity_class){
        $entity = new ReflectionClass($entity_class);
        $repository = 'App\Repository\\'.$entity->getShortName().'Repository';

        if(class_exists($repository)){
            return new $repository($entity);
        }
        return false;
    }

    /**
     * @param string|null $message
     * @param string|null $type
     */
    public function getFlash(string $message = null, string $type = null) {

        $message = $message ? $message : $this->session->get('message');
        $type = $type ? $type : $this->session->get('message_type');
        $type = $type ? $type : 'success';

        if ($message) {

            $flash = $this->render('/messages/flash_message.html.twig', [
                'type' => $type,
                'message' => $message
            ]);
            $this->session->clear('message');
            $this->session->clear('message_type');
            return $flash;
        }
    }

    /**
     * @param false $message
     * @param string $type
     * @return false
     */
    public function setFlash(bool $message = null, string $type = 'success'): bool
    {
        $this->session->set('message', $message);
        $this->session->set('message_type', $type);
        return false;
    }

    public function generateToken(): string
    {
        $csrfToken = null;
        try {
            $csrfToken = sha1(random_bytes(9));
        } catch (Exception $e) {
        }

        $this->session->set('csrf_token',$csrfToken);
        return $csrfToken;
    }

    public function redirect($status, $url = null) {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        header('Location: ' .$protocol.host.'/'.$url, true, $status);
        exit;
    }

    public function generateRoute(string $route, array $mandatory = null,$anchor = null): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        if ($mandatory) {
            foreach ($mandatory as $name => $value) {
                $route .= "/$value";
            }
        }
        if($anchor){
            $route .= "#$anchor";
        }
        return $protocol.$_SERVER['HTTP_HOST'].'/'.$route;
    }
}
