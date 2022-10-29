<?php

namespace TestTask;

final class CallDto
{
    /**
     * @var string
     */
    private $startDateTime;
    /**
     * @var int
     */
    private $duration;

    /**
     * @return string
     */
    public function getStartDateTime(): string
    {
        return $this->startDateTime;
    }

    /**
     * @param string $startDateTime
     * @return CallDto
     */
    public function setStartDateTime(string $startDateTime): CallDto
    {
        $this->startDateTime = $startDateTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     * @return CallDto
     */
    public function setDuration(int $duration): CallDto
    {
        $this->duration = $duration;
        return $this;
    }
}
