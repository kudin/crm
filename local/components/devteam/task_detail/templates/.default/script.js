$(function() { 
    
    $('.edit_task').on('click', function() {
        $('.edit_task_form').show();
        $('.task_content').hide();
        $('.edit_task').hide();
        tinymce.init({ 
            menubar: false,
            statusbar: false,
            selector: '.edit_task_form textarea',
            language: 'ru',
            plugins: ['textcolor colorpicker link media'],
            toolbar: 'undo redo | bold italic forecolor underline strikethrough | alignleft aligncenter alignright | link media',
            content_css: ['/css/tiny/style.css'], 
            height: 360
        });
    });
    
    $('.edit_cancel').on('click', function(e) {  
        $('.edit_task').show();
        $('.edit_task_form').hide();
        $('.task_content').show();
        e.preventDefault();
    });

    $('.showPanel').on('click', function() {
        id = $(this).data('id');
        $('#' + id).show();
        $(this).hide();
        return false;
    });

    tinymce.init({
        menubar: false,
        statusbar: false,
        selector: '.tiny',
        language: 'ru',
        plugins: ['textcolor colorpicker link media'],
        toolbar: 'undo redo | bold italic forecolor underline strikethrough | alignleft aligncenter alignright | link media',
        content_css: ['/css/tiny/style.css'],
        min_height: 160,
        height: 220
    });
});