$(document).ready(function () {
    // Remove video field
    $(document).on('click', '.remove-video', function (event) {
        event.preventDefault();
        $(this).closest('.video-item').remove();
    });

    // Add video field
    $('.add-video').on('click', function (event) {
        var $container = $($(this).data('target'));
        var index = $container.data('index');
        var prototype = $container.data('prototype');
        var newForm = prototype.replace(/__name__/g, index);
        $container.data('index', index + 1);
        var $videoItem = $('<div class="video-item"></div>').append(newForm);
        var $removeButton = $('<button type="button" class="btn remove-video mb-4">Supprimer</button>');
        $videoItem.append($removeButton);
        $container.append($videoItem);
    });
});
