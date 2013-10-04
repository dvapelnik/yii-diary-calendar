<?php
class Month
{
    private $_days;

    public function __construct(Date $day)
    {
        $this->_days = $day->generateDaysInCurrentMonth();
    }

    private function fillBefore()
    {
        $firstDay = reset($this->_days);

        $counter = -1;
        $currentDay = $firstDay;
        for($i = $firstDay->dow; $i > 1; $i--)
        {
            $currentDay = $currentDay->getPrevDay();
            $currentDay->inMonth = false;
            $this->_days[$counter] = $currentDay;
            $counter--;
        }

        ksort($this->_days);
    }

    private function fillAfter()
    {
        $lastDay = end($this->_days);

        $arrayKeys = array_keys($this->_days);
        $counter = end($arrayKeys) + 1;
        $currentDay = $lastDay;

        for($i = $lastDay->dow; $i < 7; $i++)
        {
            $currentDay = $currentDay->getNextDay();
            $currentDay->inMonth = false;
            $this->_days[$counter] = $currentDay;
            $counter++;
        }

        ksort($this->_days);
    }

    public function getMonth($fill = false)
    {
        if($fill)
        {
            $this->fillBefore();
            $this->fillAfter();
        }

        return $this->_days;
    }
}