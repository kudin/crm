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
            $('#userprojects').append('<option selected value="' + id + '">' + projects[usersToProjects[val][id]].name + '</option>');
        }
    });
    $('#userid').change();
});
 