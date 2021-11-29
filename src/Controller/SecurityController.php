<?php

namespace App\Controller;

use App\AbstractController;
use App\Entity\User;
use App\Service\UserService;

class SecurityController extends AbstractController
{

    public function login (){
        if($this->session->get('login')) $this->redirect(302,'user/index');
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
                $this->redirect(302,'user/index');
            } else {
                $this->setFlash($tryLoginLastError, 'danger');
            }
        }
        return $this->render('security/login.html.twig',[
            'flash' => $this->getFlash(),
            'user' => $userInputData
        ]);
    }

    public function logout (){
        if($this->session->get('login')){
            $this->session->destroy();
            $this->redirect(302,'user/logout');
        }
        $this->render('user/logout.html.twig',[
        ]);
    }

    public function register (){
        if($this->session->get('login')) $this->redirect(302,'user/index');
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
                $this->getEntityManager()->persist($user);
                $this->setFlash(200);
                $this->redirect(302,'user/login');
            } else {
                $this->setFlash($validationLastError, 'danger');
            }
        }
        $this->render('user/register.html.twig',[
            'flash' => $this->getflash(),
            'user' => $userInputData
        ]);
    }

}