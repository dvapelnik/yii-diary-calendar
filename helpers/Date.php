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
    }

    public function getDeyOfWeekVerbose()
    {
    }

    protected function getCountOfDaysInCurrentMonth()
    {
    }

    protected function getNumOfDayInYear()
    {
    }

    protected function getCountOfDaysInYear()
    {
    }

    protected function isLastDayInMonth()
    {
    }

    protected function isLastDayInYear()
    {
    }
}