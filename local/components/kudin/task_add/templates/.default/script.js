$(function () {
    tinymce.init({
        menubar: false,
        statusbar: false,
        selector: '.tiny',
        language: 'ru',
        plugins: ['textcolor colorpicker link media'],
        toolbar: 'undo redo | bold italic forecolor underline strikethrough | bullist numlist | alignleft aligncenter alignright | indent outdent | removeformat link  media ',
        content_css: ['/css/tiny/style.css'],
        min_height: 250,
        height: 320,
        gecko_spellcheck:true
    }); 
    
    $(document).on('click', '.add-files', function(e) {
        $(this).removeClass('add-files').addClass('add-more-files').html('<i class="fa fa-plus"></i>  Добавить ещё файлы');
        $('.hiddenfiles').show();
        e.preventDefault(); 
    });
    
    $(document).on('click', '.customer_label a', function(e) {
       $(this).parent().remove();
       $('.select-customer').show();
    });
   
    $(document).on('click', '.add-more-files', function(e) { 
        $('.hiddenfiles').append('<label class="form-control"><input type="file" name="attach[]"></label>');
        e.preventDefault(); 
    });
    
    $('#priory').change(function() {
        $('#priory').attr('class', 'form-control prior' + $('#priory').val());
    });
}); 