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
                'actions'    => array('edit', 'remove',),
                'users'      => array('@'),
                'expression' => 'Event::model()->canChange()',
            ),
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
            $dayOfThisMonth = new Date($year, $month, empty($_GET) ? date('d') : 1);
            $month = new Month($dayOfThisMonth);
        } catch(CException $e)
        {
            throw new CHttpException(502);
        }

        $currentDay = new Date(date('Y'), date('n'), date('d'));

        Yii::import('calendar.helpers.TextHelper.*.*');

        $this->render(
            'index',
            array(
                'dayOfThisMonth' => $dayOfThisMonth,
                'currentDay'     => $currentDay,
                'month'          => $month,
            )
        );
    }

    public function actionAdd()
    {
        $type = Yii::app()->request->getParam('type', null);
        $timestamp = Yii::app()->request->getParam('timestamp', null);

        if($type && $timestamp && preg_match('/^(note|appo)$/', $type))
        {
            /**
             * @var $calendarModel Event
             */
            $calendarModel = new Event();

            if(isset($_POST['Event']))
            {
                $calendarModel->attributes = $_POST['Event'];
                $calendarModel->owner = Yii::app()->user->id;
                $calendarModel->type = $type;

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
                ),
                false,
                true
            );
        } else
        {
            throw new CHttpException(404);
        }
    }

    public function actionEdit()
    {
        if(isset($_GET['id']) && preg_match('/^\d+$/', $_GET['id']))
        {
            /**
             * @var $event Event
             */
            $event = Event::model()->findByPk($_GET['id']);

            if(isset($_POST['Event']))
            {
                $event->text = $_POST['Event']['text'];
                $event->save();

                $date = Date::GetFromUNIX($event->timestamp);

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

            if($event !== null)
            {
                $this->renderPartial('edit',
                    array(
                        'calendarModel' => $event,
                        'header'        => $event->type == 'note' ? 'Note' : 'Appointment',
                        'isEdit'        => true,
                    ),
                    false,
                    true
                );
            }
        } else
        {
            throw new CHttpException(404);
        }
    }

    public function actionRemove()
    {
        if(isset($_GET['id']) && preg_match('/^\d+$/', $_GET['id']))
        {
            Event::model()->deleteByPk($_GET['id']);
            $this->redirect(Yii::app()->request->urlReferrer);
        }
    }
}