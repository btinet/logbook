<?php

namespace App\Entity;

use DateTime;

class Task
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var DateTime
     */
    public DateTime $dueDate;

    /**
     * @var int
     */
    public int $user_id;

    /**
     * @var int
     */
    public int $tag_id;

    /**
     * @var string
     */
    public string $description;

    /**
     * @var bool
     */
    public bool $notice_user;

    /**
     * @var bool
     */
    public bool $done;


    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return DateTime
     */
    public function getDueDate(): DateTime
    {
        return $this->dueDate;
    }

    /**
     * @param DateTime $dueDate
     */
    public function setDueDate(DateTime $dueDate): void
    {
        $this->dueDate = $dueDate;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getUser_id(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUser_id(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return int
     */
    public function getTag_id(): int
    {
        return $this->tag_id;
    }

    /**
     * @param int $tag_id
     */
    public function setTag_id(int $tag_id): void
    {
        $this->tag_id = $tag_id;
    }

    /**
     * @return bool
     */
    public function getNotice_user(): bool
    {
        return $this->notice_user;
    }

    /**
     * @param bool $notice_user
     */
    public function setNotice_user(bool $notice_user): void
    {
        $this->notice_user = $notice_user;
    }

    /**
     * @return bool
     */
    public function getDone(): bool
    {
        return $this->done;
    }

    /**
     * @param bool $done
     */
    public function setDone(bool $done): void
    {
        $this->done = $done;
    }

}
