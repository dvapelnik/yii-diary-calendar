<?php

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $year = Yii::app()->request->getParam('year', date('Y'));
        $month = Yii::app()->request->getParam('month', date('n'));

        $this->render('index');
    }
}