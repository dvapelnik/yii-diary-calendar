<?php
class CalendarEventBehavior extends CBehavior
{
    public function getCalEvents()
    {
        $date = $this->getOwner();

        $calEvents = Event::model()->findAll(
            'timestamp >= :begin AND timestamp < :end',
            array(
                ':begin' => $date->UNIX,
                ':end'   => $date->nextDay->UNIX,
            )
        );

        return $calEvents;
    }
}