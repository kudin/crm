$(function() { 
    
    $('.showPanel').on('click', function(){
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
        min_height: 150,
        height: 220
    });
});