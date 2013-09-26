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

    public function __construct($year, $month, $day = 1)
    {
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }

    //region Prev-Next
    public function getPrevDay()
    {
    }

    public function getNextDay()
    {
    }

    public function getPrevMonth()
    {
    }

    public function getNextMonth()
    {
    }

    public function getPrevYear()
    {
    }

    public function getNextYear()
    {
    }

    //endregion

    public function generateDaysInCurrentMonth()
    {
    }

    public function getDayOfWeekNumber()
    {
        return jddayofweek($this->toJDCalendar(), 0);
    }

    public function getDeyOfWeekVerbose($isShort = false)
    {
        return jddayofweek($this->toJDCalendar(), $isShort ? 2 : 1);
    }

    protected function toJDCalendar()
    {
        return gregoriantojd($this->month, $this->day, $this->year);
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
}