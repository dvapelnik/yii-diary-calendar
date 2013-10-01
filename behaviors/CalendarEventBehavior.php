<?php
class CalendarEventBehavior extends CBehavior
{
    public function getCalEvents()
    {
        $date = $this->getOwner();

        $calEvents = Event::model()->findAll(
            'timestamp >= :begin AND timestamp < :end AND owner = :user_id',
            array(
                ':begin' => $date->UNIX,
                ':end'   => $date->nextDay->UNIX,
                ':user_id' => Yii::app()->user->id,
            )
        );

        return $calEvents;
    }
}