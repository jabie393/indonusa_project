// Handle all sidebar links with .ajax-link class
$(document).on('click', '.ajax-link', function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    $.get(url, function (data) {
        var html = $('<div>').html(data);
        var newMain = html.find('#main-content').html();
        var newSidebar = html.find('#sidebar').html();
        if (newMain) $('#main-content').html(newMain);
        if (newSidebar) $('#sidebar').html(newSidebar);
        history.pushState({ url: url }, '', url);
    });
});

// Handle browser back/forward buttons
window.onpopstate = function (event) {
    if (event.state && event.state.url) {
        $.get(event.state.url, function (data) {
            var html = $('<div>').html(data);
            var newMain = html.find('#main-content').html();
            var newSidebar = html.find('#sidebar').html();
            if (newMain) $('#main-content').html(newMain);
            if (newSidebar) $('#sidebar').html(newSidebar);
        });
    }
};