<?php

namespace App;


use App\Extension\Twig\TwigExtension;
use App\Service\EntityManagerService;
use App\Service\Translation;
use DateTime;
use Exception;
use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Knp\Bundle\TimeBundle\Twig\Extension\TimeExtension;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Translation\Translator;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class AbstractController
{

    /**
     * @var Environment
     */
    protected Environment $view;
    protected Session $session;
    protected array $trans;
    protected Request $request;
    protected DateTime $now;
    protected DateTimeFormatter $formatter;

    /**
     * @throws ReflectionException
     */
    function __construct(){

        $debug = $_ENV['APP_ENV'] === 'development';

        $this->session = new Session();
        $this->session->init();
        $trans = new Translation($this->session);
        $this->trans = $trans->parse();

        $this->request = new Request($this->session->get('csrf_token'));
        $this->generateToken();

        $this->now =new DateTime();


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
        $this->view->addGlobal('user',$this->getUser());
        $this->view->addGlobal('csrf_token',$this->session->get('csrf_token'));
        $this->view->addGlobal('trans',$this->trans);
        $this->view->addGlobal('locale',$trans->locale);
        $this->view->addGlobal('locales',$trans->availableLanguages);
        $this->view->addGlobal('now',$this->now);
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
            return $e->getMessage();
        }
    }

    public function getEntityManager(): EntityManagerService
    {
        return new EntityManagerService();
    }

    /**
     * @throws ReflectionException
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
    public function getFlash(string $message = null, string $type = null): ?string
    {

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
        return null;
    }

    /**
     * @param string $message
     * @param string $type
     * @return void
     */
    public function setFlash(string $message, string $type = 'success'): void
    {
        $this->session->set('message', $message);
        $this->session->set('message_type', $type);
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

    /**
     * @throws ReflectionException
     */
    public function getUser()
    {
        if($this->session->get('login') && $this->session->get('user'))
        {
            $userRepository = $this->getRepository($_ENV['USER_ENTITY']);
            return $userRepository->findOneBy([
                'id' => $this->session->get('user')
            ]);
        }
        return null;
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

    /**
     * @throws ReflectionException
     */
    public function denyUnlessGranted(string $role = null): bool
    {
        if(!$user = $this->getUser()){
            $this->setFlash(400,'warning');
            $this->redirect(302,'login');
        }

        $userRole = $user['roles'];

        if($role && !in_array($role,json_decode($userRole))){
            $this->setFlash(401,'danger');
            $this->redirect(302,'logout');
        }
        return true;
    }
}
