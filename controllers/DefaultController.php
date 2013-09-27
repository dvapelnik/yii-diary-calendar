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
            VarDumper::dump($currentDay->getUNIX());
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

    public function actionAdd()
    {
    }

    public function actionEdit()
    {
    }

    public function actionRemove()
    {
    }
}