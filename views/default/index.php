<?php
/**
 * @var $currentDay Date
 * @var $month Month
 * @var $this DefaultController
 */

$assetsUrl = $this->module->assetsUrl;

if(Yii::app()->clientScript->getPackageBaseUrl('jquery') === false)
{
    Yii::app()->getClientScript()->registerCoreScript('jquery');
}

Yii::app()->getClientScript()->registerScriptFile($assetsUrl . '/js/main.js', CClientScript::POS_END);
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
                                        'data-cal-unix' => $day->getUNIX(),
                                        'data-action'   => 'appo',
                                    )
                                );
                                ?>
                            </div>
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