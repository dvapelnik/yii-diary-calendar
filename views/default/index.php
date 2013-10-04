<?php
/**
 * @var $currentDay Date
 * @var $month Month
 * @var $this DefaultController
 */

$assetsUrl = $this->module->assetsUrl;

$clientScript = Yii::app()->getClientScript();
if(Yii::app()->clientScript->getPackageBaseUrl('jquery') === false)
{
    $clientScript->registerCoreScript('jquery');
}

$clientScript->registerScriptFile($assetsUrl . '/js/main.js', CClientScript::POS_END);
?>

    <link rel="stylesheet" type="text/css" href="<?php echo $assetsUrl; ?>/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $assetsUrl; ?>/css/mixtures.css">

    <h1><?php echo Yii::t('CalendarModule.main', 'Calendar') ?></h1>

    <div id="calendar">
        <div class="calendar-header">
            <div class="prev-month">
                <?php
                echo CHtml::link(
                    sprintf(
                        '%s %s',
                        $currentDay->getPrevMonth()->monthVerbose,
                        $currentDay->getPrevMonth()->year
                    ),
                    Yii::app()->createUrl(
                        'calendar/default/index',
                        array(
                            'month' => $currentDay->getPrevMonth()->month,
                            'year'  => $currentDay->getPrevMonth()->year,
                        )
                    )
                );
                ?>
            </div>
            <div class="current">
                <h2><?php echo sprintf('%s %s', $currentDay->monthVerbose, $currentDay->year); ?></h2>
            </div>
            <div class="next-month">
                <?php
                echo CHtml::link(
                    sprintf(
                        '%s %s',
                        $currentDay->getNextMonth()->monthVerbose,
                        $currentDay->getNextMonth()->year
                    ),
                    Yii::app()->createUrl(
                        'calendar/default/index',
                        array(
                            'month' => $currentDay->getNextMonth()->month,
                            'year'  => $currentDay->getNextMonth()->year,
                        )
                    )
                );
                ?>
            </div>
        </div>
        <div class="calendar-body">
            <table id="calendar-table">
                <thead>
                <tr>
                    <?php foreach(Date::GetDaysOfWeek('abbreviated', true) as $day): ?>
                        <td><?php echo $day ?></td>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <?php foreach ($month->getMonth(true) as $day): ?>
                    <td <?php if (!$day->inMonth): ?>class="day-locked" <?php endif ?>>
                        <div class="day-container">
                            <div class="day-header">
                                <?php
                                echo $day->day;
                                $assetsPath = $this->module->getAssetsUrl();
                                ?>
                                <div class="float-left">
                                    <?php
                                    echo CHtml::link(
                                        CHtml::image($assetsPath . '/images/note.png'),
                                        '#',
                                        array(
                                            'data-cal-unix' => $day->getUNIX(),
                                            'data-action'   => 'note',
                                        )
                                    );
                                    ?>
                                </div>
                                <div class="float-left">
                                    <?php
                                    echo CHtml::link(
                                        CHtml::image($assetsPath . '/images/appo.png'),
                                        '#',
                                        array(
                                            'data-cal-unix' => $day->UNIX,
                                            'data-action'   => 'appo',
                                        )
                                    );
                                    ?>
                                </div>
                            </div>
                            <div class="day-events">
                                <?php if($dayEvents = $day->calEvents): ?>
                                    <?php
                                    /**
                                     * @var $event Event
                                     */
                                    foreach($dayEvents as $event)
                                    {
                                        echo CHtml::link(
                                            Text::limit_chars(
                                                str_replace('\\', '', strip_tags($event->text)),
                                                10
                                            ),
                                            '#',
                                            array(
                                                'data-event-id'   => $event->id,
                                                'data-event-type' => $event->type,
                                            )
                                        );
                                    }
                                    ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <?php if ($day->dow % 7 == 0): ?>
                </tr>
                <tr>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
                </tbody>
            </table>
        </div>
    </div>



<?php
$this->widget('calendar.widgets.modal.Modal',
    array(
        'modalId'             => 'calendar-modal',
        'replacePlace'        => 'place',
        'includedScriptFiles' => array(
            $assetsUrl . '/js/ajax.js',
        ),
    )
);
?>