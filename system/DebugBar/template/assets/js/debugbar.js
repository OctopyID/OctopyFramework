$(document).ready(function() {
    $('.debugbar').resizable({
        maxHeight: 450,
        minHeight: 34,
        handles: 'n, s',
        stop: function(event, ui) {
            var height = ui.size.height;
        }
    });

    $('.debugbar_logo').click(function() {
        $('.debugbar_tab, .debugbar_content').toggle();
        $('.debugbar').toggleClass('debugbar_hide').removeClass('ui-resizable').attr('style', '');
    });

    $('button.debugbar_tablinks').click(function(event) {

        // button
        $('button.debugbar_tablinks').removeClass('debugbar_active');

        $(this).addClass('debugbar_active');

        // content
        if ($('div.debugbar_content > #' + this.id).css('display') === 'block') {
            $(this).removeClass('debugbar_active');
            return $('.debugbar_content > div#' + this.id).toggle();
        }

        $('.debugbar_tabcontent').each(function(index, element) {
            if ($(element).css('display') === 'block' && this.id === element.id) {
                $(element).toggle();
            }
        });

        $('div#' + this.id).toggle();
    });
});