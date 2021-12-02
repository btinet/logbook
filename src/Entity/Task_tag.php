<?php

namespace App\Entity;

use DateTime;

class Task_tag
{
    /**
     * @var int
     */
    public int $task_id;

    /**
     * @var int
     */
    public int $tag_id;

    /**
     * @return int
     */
    public function getTaskId(): int
    {
        return $this->task_id;
    }

    /**
     * @param int $task_id
     */
    public function setTaskId(int $task_id): void
    {
        $this->task_id = $task_id;
    }

    /**
     * @return int
     */
    public function getTagId(): int
    {
        return $this->tag_id;
    }

    /**
     * @param int $tag_id
     */
    public function setTagId(int $tag_id): void
    {
        $this->tag_id = $tag_id;
    }

}
