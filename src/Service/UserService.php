<?php


namespace App\Service;


/**
 * Class User
 * @package Btinet\Ringhorn
 */
class UserService
{

    /**
     * @var PasswordService
     */
    public PasswordService $password;

    /**
     * User constructor.
     */
    function __construct(){
        $this->password = new PasswordService();
    }

    /**
     * @param $user         // User-Objekt, dass die Datenbanktabelle widerspiegelt
     * @param $repository   // Model-Repository, das die Methoden zum Abrufen enthält
     * @param array $data   // Formulardaten, die per POST übergeben worden sind
     * @return int|mixed    // Gibt entweder Fehlercode oder das User-Objekt zurück
     */
    public function validate($user, $repository, array $data)
    {
        if(0 != ($usernameLastError = $this->isString('username',$data,21031))) return $usernameLastError;
        if(0 != ($usernameLastError = $this->isUnique($repository,'username',$data,21011))) return $usernameLastError;
        if(0 != ($emailLastError = $this->isUnique($repository,'email',$data,21012))) return $emailLastError;
        if(0 != ($emailLastError = $this->isEmail('email',$data,21022))) return $emailLastError;
        if(0 != ($passwordLastError = $this->password->validate($data['password']))) return $passwordLastError;
        if(0 != ($firstnameLastError = $this->isString('firstname',$data,21034))) return $firstnameLastError;
        if(0 != ($lastnameLastError = $this->isString('lastname',$data,21035))) return $lastnameLastError;

        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setPassword($this->password->hash($data['password']));
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        $user->setIsActive(1);
        $user->setISBlocked(0);

        return $user;
    }

    /**
     * @param $repository
     * @param $needle
     * @param $array
     * @param int $errorCode
     * @return int
     */
    protected function isUnique($repository, $needle, $array, int $errorCode = 2101){
        return ($repository->findOneBy([$needle => $array[$needle]])) ? $errorCode : 0;
    }

    /**
     * @param $needle
     * @param $array
     * @param int $errorCode
     * @return int
     */
    protected function isEmail($needle, $array, int $errorCode = 2102){
        return (!filter_var($array[$needle], FILTER_VALIDATE_EMAIL)) ? $errorCode : 0;
    }

    /**
     * @param $needle
     * @param $array
     * @param int $errorCode
     * @return int
     */
    protected function isString($needle, $array, int $errorCode = 2103){
        return (!is_string($array[$needle])) ? $errorCode : 0;
    }

}
