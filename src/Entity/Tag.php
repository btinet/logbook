<?php

namespace App\Entity;

use DateTime;

class Tag
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var int
     */
    public int $user;


    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getUser(): int
    {
        return $this->user;
    }

    /**
     * @param int $user
     */
    public function setUser(int $user): void
    {
        $this->user = $user;
    }

}
