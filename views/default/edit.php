<?php
/**
 * @var $this DefaultController
 * @var $calendarModel Event
 * @var $form CActiveForm
 * @var $header string
 * @var $isEdit boolean
 */

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
        if(isset($isEdit) && $isEdit)
        {
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
        }
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