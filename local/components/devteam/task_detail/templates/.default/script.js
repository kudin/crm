$(function() { 
    tinymce.init({
        menubar: false,
        statusbar: false,
        selector: '.tiny',
        language: 'ru',
        plugins: ['textcolor colorpicker link media'],
        toolbar: 'undo redo | bold italic forecolor | alignleft aligncenter alignright | link media',
        content_css: ['/css/tiny/style.css'],
        min_height: 150,
        height: 220
    });  
});