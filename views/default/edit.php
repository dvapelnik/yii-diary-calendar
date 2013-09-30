<?php
/**
 * @var $this DefaultController
 * @var $calendarModel Event
 * @var $form CActiveForm
 * @var $header string
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