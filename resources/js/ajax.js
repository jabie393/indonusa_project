// Handle all sidebar links with .ajax-link class
$(document).on('click', '.ajax-link', function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.get(url, function (data) {
        var html = $('<div>').html(data);
        var newContent = html.find('#main-content').html();
        $('#main-content').html(newContent ? newContent : data);
    });
});