$(function() {
    $(document).on('click', '.adv_filterbtn', function() {
        $('.tasks_advanced_filter').toggle();
    });
    
    $(document).on('change', '#projects_list', function() {
        location.href = "/tasks/" + $(this).val() + "/"; 
    }); 

    $(document).on('click', '[data-mass-delete]', function(event) {
        if (confirm("Вы действительно хотите удалить выбранные задачи?")) {
            var ids = [];
            $('#tasks_list tbody input[type=checkbox]:checked').each(function() {
                id = $(this).val();
                ids.push(id); 
            });
            $.ajax({ 
                url: '/api/',
                dataType: 'json',
                data: {action: "deleteTasks", id: ids}, 
                success: function(data) {
                    if(data.error != undefined) {
                        new PNotify({title :'Ошибка',
                                    text: data.error,
                                    type: 'error' });
                    } else {
                        $('#tasks_list tbody input[type=checkbox]:checked').each(function() { 
                            $('#task' + id).animate({opacity: 0.0 }, 600, function () { $(this).remove(); });
                        });
                    }
                }
            });
        } 
        event.preventDefault();
    });

    /* icheck */
 
    $('input.flat').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });
   
    $('table input').on('ifChecked', function () {
        check_state = '';
        $(this).parent().parent().parent().addClass('selected');
        countChecked();
    });
    $('table input').on('ifUnchecked', function () {
        check_state = '';
        $(this).parent().parent().parent().removeClass('selected');
        countChecked();
    });

    var check_state = '';
    $('.bulk_action input').on('ifChecked', function () {
        check_state = '';
        $(this).parent().parent().parent().addClass('selected');
        countChecked();
    });
    $('.bulk_action input').on('ifUnchecked', function () {
        check_state = '';
        $(this).parent().parent().parent().removeClass('selected');
        countChecked();
    });
    $('.bulk_action input#check-all').on('ifChecked', function () {
        check_state = 'check_all';
        countChecked();
    });
    $('.bulk_action input#check-all').on('ifUnchecked', function () {
        check_state = 'uncheck_all';
        countChecked();
    });

    function countChecked() {
        if (check_state == 'check_all') {
            $(".bulk_action input[name='table_records']").iCheck('check');
        }
        if (check_state == 'uncheck_all') {
            $(".bulk_action input[name='table_records']").iCheck('uncheck');
        }
        var n = $(".bulk_action input[name='table_records']:checked").length;
        if (n > 0) {
            $('.column-title').hide();
            $('.bulk-actions').show();
            $('.action-cnt').html(n);
        } else {
            $('.column-title').show();
            $('.bulk-actions').hide();
        }
    } 
    
});