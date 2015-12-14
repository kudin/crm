$(function () {
    $(document).on('click', 'a[data-deleteproject]', function (e) {
        id = $(this).data('deleteproject');
        name = $('#project' + id).find('.project_name a').text();
        if (confirm("Вы действительно хотите удалить проект \"" + name + "\"")) {
            $.ajax({
                url: '/api/',
                data: {action: "deleteProject",
                       id: id}
            });
            $('#project' + id).animate({opacity: 0.0, }, 600, function () {
                $(this).remove();
            });
        }
    });
});