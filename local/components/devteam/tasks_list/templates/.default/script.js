$(function() {
    $(document).on('click', '.adv_filterbtn', function() {
        $('.tasks_advanced_filter').toggle();
    });
    
    $(document).on('change', '#projects_list', function() {
        location.href = "/tasks/" + $(this).val() + "/"; 
    }); 
    
});