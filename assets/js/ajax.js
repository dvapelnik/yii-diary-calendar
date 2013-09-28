$(document).ready(function ()
{
    var modalId = '#calendar-modal';
    $('a[data-cal-unix]').on('click', function (event)
    {
        $(modalId).show(500);
    });
});