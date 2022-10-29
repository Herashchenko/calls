<?php

namespace TestTask;

class MaxCallPerMinutesDto
{
    /**
     * @var \DateTime
     */
    private $dateTime;
    private $callsCount = 0;

    /**
     * @return \DateTime
     */
    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    /**
     * @param \DateTime $dateTime
     * @return MaxCallPerMinutesDto
     */
    public function setDateTime(\DateTime $dateTime): MaxCallPerMinutesDto
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getCallsCount(): int
    {
        return $this->callsCount;
    }

    /**
     * @param int $callsCount
     * @return MaxCallPerMinutesDto
     */
    public function setCallsCount(int $callsCount): MaxCallPerMinutesDto
    {
        $this->callsCount = $callsCount;
        return $this;
    }
}
