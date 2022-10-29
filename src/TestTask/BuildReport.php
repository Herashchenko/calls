<?php
namespace TestTask;


class BuildReport extends CallsReport
{

    /**
     * @param string $json
     * @return CallDtoSpl
     */
    public function fillCallDtoSpl(string $json): CallDtoSpl
    {
        $callDtoSpl = new CallDtoSpl();
        foreach (json_decode($json) as $call){
            $callDto = new CallDto();
            $callDto->setStartDateTime($call->start_date_time);
            $callDto->setDuration($call->duration_seconds);
            $callDtoSpl->setItem($callDto);
        }

        return $callDtoSpl;
    }

    /**
     * @param CallDtoSpl $dto
     * @return array
     */
    public function prepareData(CallDtoSpl $dto): array
    {
        $items = $dto->getItems();
        $calls = [];
        foreach ($items as $item){
            $startDateTime = strtotime($item->getStartDateTime());
            for ($i = $startDateTime; $i <= $startDateTime + $item->getDuration(); $i++) {
                if (!isset($calls[$i])) {
                    $calls[$i] = 0;
                }
                $calls[$i]++;
            }
        }

        ksort($calls);

        return $calls;
    }

    /**
     * Выводит массив, в котором представлена информация по меткам вермени,
     * в котрых был превышен (>) лимит CallsReport::$maxCallPerOneSecond
     *
     * @param CallDtoSpl $dto
     * @return array -  массив вида unixtimestamp => int количество звонков
     */
    protected function getOverLoadCalls(CallDtoSpl $dto): array
    {
        $calls = $this->prepareData($dto);

        foreach ($calls as $time => $countCall) {
            if ($countCall <= self::$maxCallPerOneSecond) {
                unset($calls[$time]);
            }
        }

        return $calls;
    }

    /**
     * Наполнть MaxCallPerMinutesDtoSpl поминутными данными
     * начиная с 00:00 дня по 23:59 в порядке возрастания без пропуска минут (полный день)
     * @param CallDtoSpl $dto
     * @return MaxCallPerMinutesDtoSpl
     * @throws \Exception
     */
    protected function getMaxCallPerMinutes(CallDtoSpl $dto): MaxCallPerMinutesDtoSpl
    {
        $calls = $this->prepareData($dto);
        $maxCallPerMinutesDtoSpl = new MaxCallPerMinutesDtoSpl();

        $startDay = strtotime(date('Y-m-d 00:00:00', array_key_first($calls)));
        $endDay = strtotime(date('Y-m-d 23:59:59', array_key_first($calls)));
        for ($startTime = $startDay; $startTime <= $endDay; $startTime += 60) {
            $currentCalls = array_filter($calls, function ($key) use ($startTime) {
                return $startTime <= $key && $startTime + 60 >= $key;
            }, ARRAY_FILTER_USE_KEY);

            $maxCallPerMinutesDto = new MaxCallPerMinutesDto();
            $maxCallPerMinutesDto->setDateTime(new \DateTime(date('Y-m-d H:i:s', $startTime)));
            $maxCallPerMinutesDto->setCallsCount(count($currentCalls) ? max($currentCalls) : 0);

            $maxCallPerMinutesDtoSpl->setItem($maxCallPerMinutesDto);
        }

        return $maxCallPerMinutesDtoSpl;
    }
}
