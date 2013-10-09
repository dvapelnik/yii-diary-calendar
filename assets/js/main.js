$('body').on('click', 'a[href=#]', function (event)
{
    return false;
});

$(document).on('ready', function (event)
{
    var maxHeight = 0;
    $('.day-container').each(function (index, element)
    {
        var currentHeight = $(this).height();
        if (maxHeight < currentHeight)
        {
            maxHeight = currentHeight;
        }
    }).height(maxHeight);
});