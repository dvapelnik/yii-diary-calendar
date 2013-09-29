$(document).ready(function ()
{
    $('#modal-close-button a').on('click', function (event)
    {
        hideModal();
    });

    resizeModal();

    $('.modal-widget .modal-container').on('click', function (event)
    {
        if (event.target !== this)
        {
            return;
        }
        hideModal();
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
        resizeModal();
    });
});

function hideModal()
{
    $('.modal-widget').hide(500);
    $('#' + replacePath).html('');
}

function resizeModal()
{
    $('.modal-widget .modal-container').height($(window).height())
}