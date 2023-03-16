$(document).ready(function () {
    $('.add-video').on('click', function (event) {
        var $container = $($(this).data('target'));
        var index = $container.data('index');
        var prototype = $container.data('prototype');
        var newForm = prototype.replace(/__name__/g, index);
        $container.data('index', index + 1);
        $(this).before(newForm);
    });
});