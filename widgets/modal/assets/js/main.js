$(document).ready(function ()
{
    $('#modal-close-button a').on('click', function (event)
    {
        hideModal();
    });

    resizeModal();

    $('.modal-widget .modal-container').on('click', function ()
    {
        hideModal();
    });

    $('.modal-widget .modal-container .modal').on('click', function ()
    {
        return false;
    });

    $(window).on('keypress', function (event)
    {
        if (event.keyCode == 27 && $('.modal-widget').is(':visible'))
        {
            hideModal();
        }
    });

    $(window).on('resize', function (event)
    {
        console.log(event);
        resizeModal();
    });
});

function hideModal()
{
    $('.modal-widget').hide();
    $('#' + replacePath).html('');
}

function resizeModal()
{
    $('.modal-widget .modal-container').height($(window).height())
}