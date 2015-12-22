$(function () {
    tinymce.init({
        menubar: false,
        statusbar: false,
        selector: '.tiny',
        language: 'ru',
        plugins: ['textcolor colorpicker link media'],
        toolbar: 'undo redo | bold italic forecolor | alignleft aligncenter alignright | link  media ',
        content_css: ['/css/tiny/style.css'],
        min_height: 250,
        height: 320
    }); 
    
    $(document).on('click', '[data-add-files]', function(e) {
       $(this).hide();
       $('.hiddenfiles').show();
       e.preventDefault(); 
    });
});