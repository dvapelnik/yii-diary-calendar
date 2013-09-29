<?php
abstract class CalendarModelLayer extends CActiveRecord
{
    public function getDbConnection()
    {
        return Yii::app()->{Yii::app()->controller->module->dbConnection};
    }

    public function getModelTableDbPrefix()
    {
        return Yii::app()->controller->module->dbPrefix;
    }
}