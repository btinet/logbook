<?php

namespace App\Entity;

use App\Service\PasswordService;

class User
{
    /**
     * @var int
     */
    public int $id;
    /**
     * @var string
     */
    public string $username;
    /**
     * @var string
     */
    public string $password;
    /**
     * @var string
     */
    public string $email;
    /**
     * @var string
     */
    public string $firstname;
    /**
     * @var string
     */
    public string $lastname;
    /**
     * @var array
     */
    public array $roles = [];

    /**
     * @var bool
     */
    public bool $isActive;

    /**
     * @var bool
     */
    public bool $isBlocked;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->username;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = PasswordService::hash($password);
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @param bool $noJsonEncode
     * @return array|false|string
     */
    public function getRoles(bool $noJsonEncode = false)
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        if(!$noJsonEncode){
            return $array = json_encode(array_unique($roles));
        }
        return array_unique($roles);
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->isBlocked;
    }

    /**
     * @param bool $isBlocked
     */
    public function setIsBlocked(bool $isBlocked): void
    {
        $this->isBlocked = $isBlocked;
    }

}
