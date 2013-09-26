<?php
/**
 * Class Date
 * @property $year Year
 * @property $month Month
 * @property $day Day
 */
class Date extends CComponent
{
    public $year;
    public $month;
    public $day;
    public $dow;
    public $dowVerboseShort;
    public $dowVerboseLong;

    public function __construct($year, $month, $day = 1)
    {
//        $this->_formatter = new CDateFormatter(Yii::app()->language);

        $this->year = (string)$year;
        $this->month = (string)$month;
        $this->day = (string)$day;
        $this->dow = $this->getDayOfWeekNumber();
        $this->dowVerboseShort = $this->getDeyOfWeekVerbose(false);
        $this->dowVerboseLong = $this->getDeyOfWeekVerbose();

        if(!$this->isDayInMonth())
        {
            throw new CException('Day is not in month');
        }
    }

    public static function GetFromUNIX($timestamp)
    {
        $year = date('Y', $timestamp);
        $month = date('n', $timestamp);
        $day = date('d', $timestamp);

        return new Date($year, $month, $day);
    }

    public function __toString()
    {
        return sprintf("%s-%s-%s", $this->year, $this->month, $this->day);
    }

    //region Prev-Next
    public function getPrevDay()
    {
        return $this->subDays(1);
    }

    public function getNextDay()
    {
        return $this->addDays(1);
    }

    public function getPrevMonth()
    {
        return $this->subMonth(1);
    }

    public function getNextMonth()
    {
        return $this->addMonth(1);
    }

    public function getPrevYear()
    {
        return $this->subYear(1);
    }

    public function getNextYear()
    {
        return $this->addYear(1);
    }

    //endregion

    //region Add-Sub
    public function addDays($days)
    {
        $timestamp = strtotime((string)$this);
        $newTimestamp = $timestamp + $days * 24 * 60 * 60;

        return self::GetFromUNIX($newTimestamp);
    }

    public function subDays($days)
    {
        $timestamp = strtotime((string)$this);
        $newTimestamp = $timestamp - $days * 24 * 60 * 60;

        return self::GetFromUNIX($newTimestamp);
    }

    public function addMonth($month)
    {
        $newDate = $this;
        for($i = 1; $i <= $month; $i++)
        {
            $newDate = $newDate->addDays($newDate->getCountOfDaysInCurrentMonth());
        }

        return $newDate;
    }

    public function subMonth($month)
    {
        $newData = $this;
        for($i = $month; $i >= 1; $i--)
        {
            $newData = $newData->subDays($newData->day + 1);
            $newData = $newData->subDays($newData->getCountOfDaysInCurrentMonth() - 1 - $this->day);
        }

        return $newData;
    }

    public function addYear($year)
    {
        $newDate = $this;
        for($i = 1; $i <= 12 * $year; $i++)
        {
            $newDate = $newDate->addMonth(1);
        }

        return $newDate;
    }

    public function subYear($year)
    {
        $newDate = $this;
        for($i = 12 * $year; $i >= 1; $i--)
        {
            $newDate = $newDate->subMonth(1);
        }

        return $newDate;
    }

    //endregion

    public function generateDaysInCurrentMonth()
    {
        $daysInMonth = array();
        for($i = 1; $i <= $this->getCountOfDaysInCurrentMonth(); $i++)
        {
            $daysInMonth[] = new Date($this->year, $this->month, $i);
        }

        return $daysInMonth;
    }

    public function getDayOfWeekNumber()
    {
        return Yii::app()->dateFormatter->format('e', strtotime((string)$this));
    }

    public function getDeyOfWeekVerbose($isLong = true)
    {
        return Yii::app()->dateFormatter->format($isLong ? 'EEEE' : 'EEE', strtotime((string)$this));
    }

    protected function getCountOfDaysInCurrentMonth()
    {
        return cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
    }

    protected function getNumOfDayInYear()
    {
        $numDayInYear = 0;
        for($month = 1; $month < $this->month; $month++)
        {
            $numDayInYear += cal_days_in_month(CAL_GREGORIAN, $month, $this->year);
        }
        $numDayInYear += $this->day;

        return $numDayInYear;
    }

    protected function getCountOfDaysInYear()
    {
        $countDaysInYear = 0;
        for($month = 1; $month <= 12; $month++)
        {
            $countDaysInYear += cal_days_in_month(CAL_GREGORIAN, $month, $this->year);
        }

        return $countDaysInYear;
    }

    protected function isLastDayInMonth()
    {
        return $this->day == $this->getCountOfDaysInCurrentMonth();
    }

    protected function isLastDayInYear()
    {
        return $this->month == 12 && $this->day == 31;
    }

    protected function isDayInMonth()
    {
        return $this->day <= $this->getCountOfDaysInCurrentMonth();
    }

}