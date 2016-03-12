$(function () {
    
    $('#reservation').daterangepicker({
        ranges: {
            'Сегодня': [moment(), moment()],
            'Вчера': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Последние 7 дней': [moment().subtract(6, 'days'), moment()],
            'Последние 30 дней': [moment().subtract(29, 'days'), moment()],
            'Этот месяц': [moment().startOf('month'), moment().endOf('month')],
            'Прошлый месяц': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: { 
            format: 'DD.MM.YYYY',
            separator: " - ",
            applyLabel: "Применить",
            cancelLabel: "Отмена",
            fromLabel: "От",
            toLabel: "Дo",
            customRangeLabel: "Выбрать",
            daysOfWeek: [
                "Вс",
                "Пн",
                "Вт",
                "Ср",
                "Чт",
                "Пт",
                "Сб"
            ],
            monthNames: [
                "Январь",
                "Февраль",
                "Март",
                "Апрель",
                "Май",
                "Июнь",
                "Июль",
                "Август",
                "Сентябрь",
                "Октябрь",
                "Ноябрь",
                "Декабрь"
            ],
            firstDay: 1
        }
    });

    $('#userid').change(function () {
        val = $(this).val();
        $('#userprojects').html('');
        for (id in usersToProjects[val]) {
            $('#userprojects').append('<option selected value="' + usersToProjects[val][id] + '">' + projects[usersToProjects[val][id]].name + '</option>');
        }
    });
    
    $('#userid').change();
    
    $(document).on('click', '.makereport', function(e) {
        e.preventDefault();
        formdata = $('#reportform').serialize();
        $('.report-title').text('Затраты ' + $('#userid :selected').text() + ' за период с ' + $('#reservation').val().replace('-', 'по'));
        $.ajax({
            data: formdata,
            method: 'POST',
            success: function (data) {
                $('#report-area').html($(data).find('#report-area'));
                $('.report .panel_toolbox').show();
            }
        }); 
    });
 
    $('.makereport').click();
 
    $(document).on('click', '.html_link', function() {
       $('#reportform').attr('action', '/report/download/html/').submit(); 
    });
      
});
 