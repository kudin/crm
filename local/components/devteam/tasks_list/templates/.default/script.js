$(function() {
    $(document).on('click', '.adv_filterbtn', function() {
        $('.tasks_advanced_filter').toggle();
    });
     
    $(".select2_multiple").select2({
        maximumSelectionLength: 10, 
        allowClear: true
    }); 
    
});