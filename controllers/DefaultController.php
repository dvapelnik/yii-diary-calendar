<?php

class DefaultController extends Controller
{
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'users' => array('@'),
            ),
            array('deny',
                'users' => array('?'),
            ),
        );
    }

    public function actionIndex()
    {
        $year = Yii::app()->request->getParam('year', date('Y'));
        $month = Yii::app()->request->getParam('month', date('n'));

        try
        {
            $currentDay = new Date($year, $month, empty($_GET) ? date('d') : 1);
            $month = new Month($currentDay);
        } catch(CException $e)
        {
            throw new CHttpException(502);
        }

        $this->render(
            'index',
            array(
                'currentDay' => $currentDay,
                'month'      => $month,
            )
        );
    }

    public function actionEdit()
    {
        $type = Yii::app()->request->getParam('type', null);
        $timestamp = Yii::app()->request->getParam('timestamp', null);

        $class = ucfirst($type);

        if($type && $timestamp && preg_match('/^(note|appo)$/', $type) || isset($_GET['id']))
        {
            /**
             * @var $calendarModel CalendarModelLayer
             */
            $calendarModel = new $class();

            if(isset($_POST[$class]))
            {
                $calendarModel->attributes = $_POST[$class];
                $calendarModel->owner = Yii::app()->user->id;
                if($class == 'Appo')
                {
                    $calendarModel->email = Yii::app()->user->{$this->module->webUserEmailField};
                }

                $calendarModel->save();

                $date = Date::GetFromUNIX($timestamp);

                $this->redirect(
                    Yii::app()->createUrl(
                        'calendar/default/index',
                        array(
                            'month' => $date->month,
                            'year'  => $date->year,
                        )
                    )
                );
            }

            $calendarModel->timestamp = $timestamp;

            echo $this->renderPartial('edit',
                array(
                    'calendarModel' => $calendarModel,
                    'header'        => $type == 'note' ? 'Note' : 'Appointment',
                    'class'         => $class,
                ),
                false,
                true
            );
        } else
        {
            throw new CHttpException(404);
        }
    }

    public function actionRemove()
    {
    }

}