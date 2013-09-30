$(document).ready(function ()
{
    var modalId = '#calendar-modal';

    $('a[data-cal-unix]').on('click', function (event)
    {
        var link = $(event.target).parent();
        var timestamp = link.attr('data-cal-unix'),
            type = link.attr('data-action'),
            documentSelector = $(document);

        console.log(type);

        $.ajax({
            type   : 'get',
            url    : '/kit/calendar/default/edit?timestamp=' + timestamp + '&type=' + type,
            success: function (data)
            {
                $('#' + replacePath).html(data);
                $(modalId).show(500, resizeModal);
            },
            error  : function (e)
            {
                console.log(e);
                alert('Error. More information in console');
            }
        });
    });
});