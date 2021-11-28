<?php

namespace App\Service;

class PasswordService
{
    public static function hash($passwordPlain):string
    {
        return password_hash($passwordPlain, PASSWORD_DEFAULT);
    }

    public static function verify(string $plain_password, string $correct_hash): bool
    {
        return password_verify($plain_password, $correct_hash);
    }

    public static function validate(array $password):int
    {
        if(!self::isString($password))                          return 1601;    // kein String
        if(!self::isEqual($password))                           return 1602;    // stimmt nicht überein
        if(!self::hasMinLength($password))                      return 1603;    // ist nicht lang genug
        if(!self::isAllowed($password))                         return 1604;    // verwendet nicht erlaubte Zeichen
        if(0 != $isComplexLastError = self::isComplex($password)) return $isComplexLastError; // ist nicht komplex genug
        return 0; // alles in Ordnung
    }

    private static function isString($password): bool
    {
        if(is_array($password) && !empty($password)){
            $passwordIsString = true;
            foreach ($password as $item){
                if(!is_string($item)) return false;
            }
            return $passwordIsString;
        } else {
            return is_string($password);
        }
    }

    private static function isEqual(array $password): bool
    {
        return ($password[0] === $password[1]);
    }

    private static function hasMinLength($password): bool
    {
        $password = is_array($password) && !empty($password) ? array_shift($password) : $password;
        return strlen($password) >= 8;
    }

    private static function isAllowed($password): bool
    {
        // TODO: auf erlaubte Zeichen prüfen
        return true;
    }

    private static function isComplex($password): int
    {
        $password = is_array($password) && !empty($password) ? array_shift($password) : $password;
        $spaceCount = mb_strlen($password) - mb_strlen(mb_ereg_replace("\p{Zs}","",$password));
        if ($spaceCount != 0){
            $p = ($spaceCount/mb_strlen($password)*100);
            if (!$p < 15) return 16051;
        }
        if (!mb_ereg("\p{Lu}",$password)) return 16052;
        if (!mb_ereg("\p{Ll}",$password)) return 16053;
        // if (!mb_ereg("\p{Lo}",$password)) return 16054;
        if (!mb_ereg("\p{Nd}",$password)) return 16055;
        return 0;
    }
}