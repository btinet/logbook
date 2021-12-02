<?php

namespace App\Controller;

use App\AbstractController;
use App\Entity\User;
use App\Service\UserService;

class SecurityController extends AbstractController
{

    /**
     * @throws \ReflectionException
     */
    public function login (): string
    {
        if($this->session->get('login')) $this->redirect(302,'');
        $userInputData = 0;
        if($this->request->isPostRequest() && $this->request->isFormSubmitted()) {
            $userInputData = [
                'username' => $this->request->post('username'),
                'password' => $this->request->post('password')
            ];
            $userRepository = $this->getRepository(User::class);
            if(0 === ($tryLoginLastError = UserService::tryLogin($userRepository, $userInputData))){
                $user = $userRepository->findOneBy(['username' => $userInputData['username']]);
                if($user){
                    $this->session->set('user',$user['id']);
                    $this->session->set('login',true);
                }
                $this->setFlash(201);
                $this->redirect(302,'');
            } else {
                $this->setFlash($tryLoginLastError, 'danger');
            }
        }
        return $this->render('security/login.html.twig',[
            'flash' => $this->getFlash(),
            'user' => $userInputData
        ]);
    }

    public function logout (): string
    {
        if($this->session->get('login')){
            $this->session->destroy();
            $this->redirect(302,'logout');
        }
        return $this->render('security/logout.html.twig',[
        ]);
    }

    /**
     * @throws \ReflectionException
     */
    public function register (): string
    {
        if($this->session->get('login')) $this->redirect(302,'');
        $userInputData = null;
        if($this->request->isPostRequest() && $this->request->isFormSubmitted()){
            $userInputData = [
                'username' => $this->request->post('username'),
                'email' => $this->request->post('email'),
                'password' => [
                    $this->request->post('password_a'),
                    $this->request->post('password_b'),
                ],
                'firstname' => $this->request->post('firstname'),
                'lastname' => $this->request->post('lastname'),
                'isActive' => true,
                'isBlocked' => false,
                'language' => ''
            ];
            $userRepository = $this->getRepository(User::class);
            if(0 === ($validationLastError = UserService::validate($userRepository, $userInputData))){
                $user = new User();
                $user->setUsername($userInputData['username']);
                $user->setEmail($userInputData['email']);
                $user->setPassword($userInputData['password'][0]);
                $user->setFirstname($userInputData['firstname']);
                $user->setLastname($userInputData['lastname']);
                $user->setRoles(['ROLE_USER']);
                $user->setIsActive(true);
                $user->setIsBlocked(false);
                $this->getEntityManager()->persist($user);
                $this->setFlash(200);
                $this->redirect(302,'login');
            } else {
                $this->setFlash($validationLastError, 'danger');
            }
        }
        return $this->render('security/register.html.twig',[
            'flash' => $this->getflash(),
            'user' => $userInputData
        ]);
    }

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \ReflectionException
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     */
    public function update(string $column){
        if(!$this->session->get('login')) $this->redirect(302,'user/login');

        $user = $this->getUser();
        if (!property_exists($user,$column)) {
            $this->flash->add(403,'danger');
            $this->redirect(302,'user/login');
        }
        unset($user->isBlocked);
        if($this->request->isPostRequest() && $this->request->isFormSubmitted()) {

            $userRepository = $this->getRepository(User::class);

            $user = new User();

            switch($column){
                case 'username':
                    $user->setUsername($this->request->post('username'));
                    if ( 0 === ($isUniqueLastError = UserService::isUnique($userRepository,'username',['username' => $user->getUsername()],21011)) ){
                        unset($user->password);
                        $this->getEntityManager()->persist($user,$this->session->get('user'));
                        $this->flash->add(200);
                        $this->redirect(302,'user/index');
                    } else {
                        $this->flash->add($isUniqueLastError, 'danger');
                    }
                    break;
                case 'email':
                    $user->setEmail($this->request->post('email'));
                    if ( 0 !== ($isEmailLastError = UserService::isEmail('email',['email' => $user->getEmail()],21022)) ) {
                        $this->flash->add($isEmailLastError, 'danger');
                        break;
                    }
                    if ( 0 === ($isUniqueLastError = UserService::isUnique($userRepository,'email',['email' => $user->getEmail()],21012)) ){
                        unset($user->password);
                        $this->getEntityManager()->persist($user,$this->session->get('user'));
                        $this->flash->add(200);
                        $this->redirect(302,'user/index');
                    } else {
                        $this->flash->add($isUniqueLastError, 'danger');
                    }
                    break;
                case 'password':
                    $userInputData = [
                        'username' => $user->getUsername(),
                        'password' => [
                            $this->request->post('password_a'),
                            $this->request->post('password_b'),
                            $this->request->post('password_old'),
                        ],
                    ];
                    if(0 !== $passwordLastError = UserService::isMatch($userRepository,$userInputData)) {
                        $this->flash->add($passwordLastError, 'danger');
                        break;
                    }
                    if(0 != $passwordLastError = PasswordService::validate($userInputData['password'])){
                        $this->flash->add($passwordLastError, 'danger');
                        break;
                    } else {
                        $user->setPassword(array_shift($userInputData['password']));
                        $this->getEntityManager()->persist($user,$this->session->get('user'));
                        $this->flash->add(200);
                        $this->redirect(302,'user/index');
                    }
                    break;
                case 'language':

                    unset($user->password);
                    $user->setLanguage($this->request->post('language'));
                    $this->getEntityManager()->persist($user,$this->session->get('user'));
                    $this->flash->add(200);
                    $this->redirect(302,'user/index');
                    break;
                default:
                    $this->flash->add(403);
                    $this->redirect(302,'user/index');
                    break;
            }
        }

        $this->view->render('user/update.html.twig',[
            'flash' => $this->flash,
            'column' => $column,
            'user' => $user
        ]);
    }

}