<?php

namespace TestTask;

abstract class CallsReport
{
    protected static $maxCallPerOneSecond = 100;

    abstract public function fillCallDtoSpl(string $json) : CallDtoSpl;


    /**
     * Выводит массив, в котором представлена информация по меткам вермени,
     * в котрых был превышен (>) лимит Calls::$maxCallPerOneSecond
     *
     * @param CallDtoSpl $dto
     * @return array -  массив вида unixtimestamp => int количество звонков
     */
    abstract protected function getOverLoadCalls(CallDtoSpl $dto): array;

    /**
     * Наполнть MaxCallPerMinutesDtoSpl поминутными данными
     * начиная с 00:00 дня по 23:59 в порядке возрастания без пропуска минут (полный день)
     * @param CallDtoSpl $dto
     * @return MaxCallPerMinutesDtoSpl
     */
    abstract protected function getMaxCallPerMinutes(CallDtoSpl $dto): MaxCallPerMinutesDtoSpl;

    /**
     * Просто шапка
     * @return string
     */
    private function getTableHeader(): string
    {
        return '<tr><th>Метка времени в минутах, Y-m-d H:i</th>'
               . '<th>Максимальная нагрузка в период времени</th><th>Наличие перегрузки сервера</th></tr>';
    }

    /**
     * Построение данных для строк таблицы по нагрузке серверов
     * @param MaxCallPerMinutesDto $dto
     * @return string
     */
    private function getTableRow(MaxCallPerMinutesDto $dto): string
    {
        return "<td>{$dto->getDateTime()->format('Y-m-d H:i')}</td><td>{$dto->getCallsCount()}</td><td>".
               (static::$maxCallPerOneSecond < $dto->getCallsCount() ? 'overload' : 'ok')
               .'</td>';
    }

    /**
     * Построение строк таблицы по нагрузке серверов
     *
     * @param CallDtoSpl $callsDtoSpl
     * @return string
     */
    private function getTableData(CallDtoSpl $callsDtoSpl): string
    {
        $data = $this->getMaxCallPerMinutes($callsDtoSpl);
        $ret = '';
        /* @var MaxCallPerMinutesDto $dto */
        foreach ($data->getItems() as $dto) {
            $ret .= '<tr>'.$this->getTableRow($dto).'</tr>';
        }
        return $ret;
    }

    /**
     * Построить таблицу нагрузки по секундам по возрастанию
     *
     * @param CallDtoSpl $callsDtoSpl
     * @return string
     */
    public function buildServerOverLoadTable(CallDtoSpl $callsDtoSpl): string
    {
        $data = $this->getOverLoadCalls($callsDtoSpl);
        $ret =
            '<table><caption>Перегруз по секундам(calls > '.static::$maxCallPerOneSecond.' звонков)</caption>'
            .'<tr><th>Метка времени в секундах, Y-m-d H:i:s</th>'
            . '<th>Максимальная нагрузка в период времени</th></tr>';
        foreach ($data as $timeUnix => $value) {
            $ret .= '<tr><td>'.date('Y-m-d H:i:s', $timeUnix).'</td>'.'<td>'.$value.'</td>'.'</tr>';
        }
        $ret .= '</table>';
        return $ret;
    }

    /**
     * Просто таблица перегрузки
     * @param CallDtoSpl $callsDtoSpl
     * @return string
     */
    public function buildServerLoadTable(CallDtoSpl $callsDtoSpl): string
    {
        return '<table>'
               .'<caption>Максимальная нагрузка по минутам</caption>'
               .$this->getTableHeader().$this->getTableData($callsDtoSpl).'</table>';
    }
}
