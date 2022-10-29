<?php

namespace TestTask;

class MaxCallPerMinutesDtoSpl extends \SplObjectStorage
{
    private $items;

    public function setItem(MaxCallPerMinutesDto $dto): void
    {
        $this->items[] = $dto;
    }

    public function getItems() {
        return $this->items;
    }
}
