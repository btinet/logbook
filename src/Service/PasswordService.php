<?php

namespace App\Service;

/**
 * Class Password
 * @package Btinet\Ringhorn
 */
class PasswordService
{

    /**
     * @param $plain_password
     * @return false|string|null
     */
    public function hash($plain_password)
    {
        $plain_password = (is_array($plain_password)) ? array_pop($plain_password) : $plain_password;
        return password_hash($plain_password, PASSWORD_DEFAULT);
    }

    /**
     * @param string $plain_password
     * @param string $correct_hash
     * @return bool
     */
    public function verify(string $plain_password, string $correct_hash): bool
    {
        return password_verify($plain_password, $correct_hash);
    }

    /**
     * @param array $password
     * @return int
     */
    public function validate(array $password):int
    {
        if(!$this->isString($password))     return 1601;    // kein String
        if(!$this->isEqual($password))      return 1602;    // stimmt nicht überein
        if(!$this->hasMinLength($password)) return 1603;    // ist nicht lang genug
        if(!$this->isAllowed($password))    return 1604;    // verwendet nicht erlaubte Zeichen
        if(!$this->isComplex($password))    return 1605;    // ist nicht komplex genug
        return 0; // alles in Ordnung
    }

    /**
     * @param $password
     * @return bool
     */
    private function isString($password): bool
    {
        if(is_array($password)){
            foreach ($password as $item){
                $passwordIsString = (is_string($item)) ?? false;
            }
            return ($passwordIsString) ?? false;
        } else {
            return (is_string($password)) ?? false;
        }
    }

    /**
     * @param array $password
     * @return bool
     */
    private function isEqual(array $password): bool
    {
        return ($password[0] == $password[1]) ?? false;
    }

    /**
     * @param $password
     * @return bool
     */
    private function hasMinLength($password): bool
    {
        $password = (is_array($password)) ? array_pop($password) : $password;
        return (strlen($password) > 7) ?? false;
    }

    /**
     * @param $password
     * @return bool
     */
    private function isAllowed($password): bool
    {
        // TODO: auf erlaubte Zeichen prüfen
        return true;
    }

    /**
     * @param $password
     * @return bool
     */
    private function isComplex($password): bool
    {
        $password = (is_array($password)) ? array_pop($password) : $password;

        /** Das Passwort muss mindestens
         * - einen Kleinbuchstaben,
         * - einen Großbuchstaben,
         * - eine Ziffer und
         * - ein Sonderzeichen @#-_$%^&+=§!?
         * enthalten.
         * Das Passwort muss zwischen 8 und 20 Zeichen lang sein.
         */
        return (bool)preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!?]{8,20}$/', $password);
    }

}
