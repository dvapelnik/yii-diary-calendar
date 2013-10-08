<?php
/**
 * @var $this DefaultController
 * @var $calendarModel Event
 * @var $form CActiveForm
 * @var $header string
 */
Yii::app()->getClientScript()->registerCss(
    'button-class',
    ".button {
    font: bold 11px Arial;
    text-decoration: none;
    background-color: #EEEEEE;
    color: #333333;
    padding: 2px 6px 2px 6px;
    border-top: 1px solid #CCCCCC;
    border-right: 1px solid #333333;
    border-bottom: 1px solid #333333;
    border-left: 1px solid #CCCCCC;
    margin: 2px;
   }",
    'all'
);
$formId = 'cal-form';
?>

<h1 style="text-align: center"><?php echo $header; ?></h1>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
            'id' => $formId,
        )
    );
    ?>
    <div>
        <?php
        $this->widget('calendar.widgets.redactorjs.Redactor',
            array(
                'model'     => $calendarModel,
                'attribute' => 'text',
            )
        );
        ?>
    </div>
    <div style="text-align: right">
        <?php
        echo $form->hiddenField($calendarModel, 'timestamp');
        echo CHtml::link(
            'Remove',
            Yii::app()->createUrl(
                '/calendar/default/remove',
                array(
                    'id' => Yii::app()->request->getParam('id')
                )
            ),
            array(
                'style' => 'margin-right: 4px;'
            )
        );
        echo CHtml::submitButton(
            'Save',
            array(
                'style' => 'text-align: right;',
            )
        );

        ?>
    </div>
    <?php $this->endWidget(); ?>
</div>