<?php

class CalendarModule extends CWebModule
{
    private $_assetsUrl;

    public $webUserEmailField;
    public $dbConnection;
    public $dbPrefix;

    public function getUrlRules()
    {
        return array(
            'calendar'                                        => 'calendar/default/index',
            'calendar/remove/<id:\d+>'                        => 'calendar/default/remove',
            'calendar/add/<timestamp:\d+>/<type:(note|appo)>' => 'calendar/default/add',
            'calendar/edit/<id:\d+>'                          => 'calendar/default/edit',
        );
    }

    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application

        // import the module-level models and components
        $this->setImport(
            array(
                'calendar.models.*',
                'calendar.components.*',
                'calendar.messages.*.*',
                'calendar.helpers.*',
                'calendar.widgets.modal.*',
                'calendar.widgets.time.*',
            )
        );
    }

    public function beforeControllerAction($controller, $action)
    {
        if(parent::beforeControllerAction($controller, $action))
        {
            $this->checkTable(array('event'));

            return true;
        } else
        {
            return false;
        }
    }

    public function getAssetsUrl()
    {
        if($this->_assetsUrl === null)
        {
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish(
                Yii::getPathOfAlias('calendar.assets'), false, -1, YII_DEBUG
            );
        }

        return $this->_assetsUrl;
    }

    private function checkTable($tables = array())
    {
        foreach($tables as $table)
        {
            if(Yii::app()->{$this->dbConnection}->schema->getTable($this->dbPrefix . $table) === null)
            {
                $schemeTemplateFile = dirname(__FILE__) . sprintf('/schemes/%sSchemeTemplate.sql', $table);
                $queryTemplate = file_get_contents($schemeTemplateFile);
                $query = str_replace('{%prefix%}', $this->dbPrefix, $queryTemplate);
                Yii::app()->{$this->dbConnection}->createCommand($query)->query();
            }
        }
    }
}
