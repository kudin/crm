$(function() { 
    
    $(document).on('click', '.removetrack', function(e) { 
        if(confirm('Подтвердите удаление')) { 
            block = $(this).closest('[data-track]');
            id = $(block).attr('data-track');
            block.remove(); 
            $.ajax({url: '/api/',
                dataType: 'json',
                data: {action: "deleteTime", track: id}, 
                success: function(data) {
                    if(data.error != undefined) {
                        new PNotify({title :'Ошибка', text: data.error, type: 'error' });
                    } else {  
                         if(data.summ > 0) {
                             $('.alltrackingsumm p').text('Всего потрачено ' +  data.summ +' ч.'); 
                          } else {
                             $('.alltrackingsumm p').text('Затраты не внесены'); 
                          }
                    } 
                }
            });
        }
        e.preventDefault();
    });
    
    $(document).on('click', '#trackTime', function(e) { 
        var h = $('#trackh').val();
        var trackdesc = $('#trackdesc').val();
        var id = $('#trackingcol').attr('data-task-id'); 
        var err = false;
        if(!h) {
            err = 'Не введено время';
        } 
        if(err) {
            new PNotify({title :'Ошибка', text: err, type: 'error' });
        } else {
           $.ajax({url: '/api/',
                dataType: 'json',
                data: {action: "trackTime", task: id, h: h, desc:trackdesc}, 
                success: function(data) {
                    if(data.error != undefined) {
                        new PNotify({title :'Ошибка', text: data.error, type: 'error' });
                    } else {  
                        $('.alltrackingsumm p').text('Всего потрачено ' +  data.summ +' ч.'); 
                        $('#trackh, #trackdesc').val('');  
                        html = '<div data-track="' + data.ok + '" class="row trackingrow">\n\
                            <div class="col-md-1 col-sm-1 col-xs-12">\n\
                                <a class="removetrack" href="#"><i class="fa fa-remove"></i></a>\n\
                            </div>\n\
                            <div class="col-md-2 col-sm-2 col-xs-12">\n\
                              ' + h + ' ч.\n\
                            </div> \n\
                            <div class="col-md-9 col-sm-9 col-xs-12"> \n\
                                 ' + trackdesc + ' \n\
                            </div>\n\
                        </div>';
                        $('.trackingadd').before(html);
                    }
                }
            });
        }  
    });
    
    $('.edit_task_form .btn-success').on('click', function(e) { // грязновато
        e.preventDefault();
        $('.editable_show').show();
        $('.editable_hidden').hide();
        $('h2.editable_show').html($('[name=NAME_NEW]').val() + '   <div class="priorb prior' + $('#priory').val() + '">' + $('#priory').val() + '</div>');
        $('[data-edittask]').appendTo('.edit_task_form');
        $('.edit_task_form').submit();
    });
    
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
            toolbar: 'undo redo | bold italic forecolor underline strikethrough | bullist numlist | alignleft aligncenter alignright | link media',
            content_css: ['/css/tiny/style.css'], 
            height: 360, 
            gecko_spellcheck:true
        });
        $('.editable_show').hide();
        $('.editable_hidden').show();
    });
    
    $('.edit_cancel').on('click', function(e) {  
        $('.edit_task').show();
        $('.edit_task_form').hide();
        $('.task_content').show();

        $('.editable_show').show();
        $('.editable_hidden').hide();
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
        gecko_spellcheck:true,
        statusbar: false,
        selector: '.tiny',
        language: 'ru',
        plugins: ['textcolor colorpicker link media'],
        toolbar: 'undo redo | bold italic forecolor underline strikethrough | bullist numlist | alignleft aligncenter alignright | link media',
        content_css: ['/css/tiny/style.css'],
        min_height: 160,
        height: 220
    });
}); 