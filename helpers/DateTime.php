<?php
class DateTime extends Date
{
    public $hour;
    public $minute;
    public $second;

    public function __construct($year, $month, $day, $hour, $minute = 0, $second = 0)
    {
        parent::__construct($year, $month, $day);
        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
    }

    public static function GetFromUNIX($timestamp)
    {
        $fromParent = parent::GetFromUNIX($timestamp);
        $fromParent->hour = date('H', $timestamp);
        $fromParent->minute = date('i', $timestamp);
        $fromParent->second = date('s', $timestamp);

        return $fromParent;
    }

    public function getUNIX()
    {
        if($this->_unix === null)
        {
            $this->_unix = strtotime(
                sprintf(
                    '%4s-%2s-%2s %2s:%2s:%2s',
                    $this->year,
                    $this->month,
                    $this->day,
                    $this->hour,
                    $this->minute,
                    $this->second
                )
            );
        }

        return $this->_unix;
    }

    public function __toString()
    {
        return Yii::app()->locale->dateFormatter->format('yyyy-MM-dd kk:mm:ss', $this->getUNIX());
    }

}