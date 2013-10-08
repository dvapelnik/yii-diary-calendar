$(document).ready(function ()
{
    var modalId = '#calendar-modal';

    var onSuccessAction = function (data)
    {
        $('#' + replacePath).html(data);
        $(modalId).show(500, resizeModal);
    };

    var onErrorAction = function (e)
    {
        console.log(e);
        alert('Error. More information in console');
    };

    $('a[data-cal-unix]').on('click', function (event)
    {
        var link = $(event.target).parent();
        var timestamp = link.attr('data-cal-unix'),
            type = link.attr('data-action'),
            documentSelector = $(document);

        $.ajax({
            type   : 'get',
            url    : '/kit/calendar/default/add?timestamp=' + timestamp + '&type=' + type,
            success: onSuccessAction,
            error  : onErrorAction
        });
    });

    $('a[data-event-id]').on('click', function (event)
    {
        var id = $(event.target).attr('data-event-id');

        $.ajax({
            type   : 'get',
            url    : '/kit/calendar/default/edit?id=' + id,
            success: onSuccessAction,
            error  : onErrorAction
        })
    });
});