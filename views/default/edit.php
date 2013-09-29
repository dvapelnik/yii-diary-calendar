<?php
/**
 * @var $this DefaultController
 * @var $calendarModel CalendarModelLayer
 * @var $form CActiveForm
 * @var $header string
 * @var $class string
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
    <?php if($class === 'Appo'): ?>
        <div class="row">
            <?php
            echo $form->checkBox($calendarModel, 'send');
            echo $form->labelEx(
                $calendarModel,
                'send',
                array(
                    'style' => 'display: inline-block; font-size: 16px; margin-left: 4px;'
                )
            );
            ?>
        </div>
    <?php endif; ?>
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